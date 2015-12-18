#!/bin/bash

function pre_install {
    wget -qO - https://packages.elastic.co/GPG-KEY-elasticsearch | sudo apt-key add -
    echo "deb http://packages.elastic.co/elasticsearch/1.7/debian stable main" | sudo tee -a /etc/apt/sources.list.d/elasticsearch-1.7.list
    debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
    debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
    apt-get update
    apt-get install -y git-core
}

function post_install {
    su -c "echo 'cd /vagrant' >> .bashrc" vagrant
    (crontab -l 2>/dev/null; echo "*/5 * * * * php /vagrant/app/console swiftmailer:spool:send --env=prod") | crontab -
}

function php_install {
    set -e
    PHP_VERSION=7

    # Dependencies
    apt-get install -y \
        bison \
        g++ \
        autoconf \
        libxml2-dev \
        libbz2-dev \
        libcurl4-openssl-dev \
        libltdl-dev \
        libpng12-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libxpm-dev \
        libimlib2-dev \
        libicu-dev \
        libreadline6-dev \
        libmcrypt-dev \
        libxslt1-dev

    rm -rf /etc/php7
    rm -rf /usr/local/php${PHP_VERSION}

    mkdir -p /etc/php7/conf.d
    mkdir -p /etc/php7/{cli,fpm}/conf.d
    mkdir -p /usr/local/php${PHP_VERSION}

    # Download
    rm -rf php-src
    wget -cq -O php.tar.gz https://github.com/php/php-src/archive/php-7.0.0.tar.gz
    mkdir -p php-src
    tar xf php.tar.gz -C ./php-src --strip-components 1
    cd php-src
    ./buildconf --force

    # Tested and works with LibreSSL 2.2.4
    # Just point --with-openssl=<your_dir>/libressl-2.2.4/.openssl to your installation
    CONFIGURE_STRING="--prefix=/usr/local/php${PHP_VERSION} \
                      --enable-bcmath \
                      --with-bz2 \
                      --with-zlib \
                      --enable-zip \
                      --enable-calendar \
                      --enable-exif \
                      --enable-ftp \
                      --with-gettext \
                      --with-gd \
                      --with-jpeg-dir \
                      --with-png-dir \
                      --with-freetype-dir \
                      --with-xpm-dir \
                      --enable-mbstring \
                      --enable-mysqlnd \
                      --with-mysqli=mysqlnd \
                      --with-pdo-mysql=mysqlnd \
                      --with-openssl \
                      --enable-intl \
                      --enable-soap \
                      --with-readline \
                      --with-curl \
                      --with-mcrypt \
                      --with-xsl \
                      --without-pear \
                      --disable-cgi"

    # Build CLI

    ./configure \
        $CONFIGURE_STRING \
        --enable-pcntl \
        --with-config-file-path=/etc/php7/cli \
        --with-config-file-scan-dir=/etc/php7/cli/conf.d

    make -j2
    make install

    # Build FPM

    make distclean
    ./buildconf --force

    ./configure \
        $CONFIGURE_STRING \
        --with-config-file-path=/etc/php7/fpm \
        --with-config-file-scan-dir=/etc/php7/fpm/conf.d \
        --disable-cli \
        --enable-fpm \
        --with-fpm-user=vagrant \
        --with-fpm-group=vagrant

    make -j2
    make install

    # Install config files

    cp php.ini-production /etc/php7/cli/php.ini
    cp php.ini-production /etc/php7/fpm/php.ini
    sed -i 's/;date.timezone =.*/date.timezone = UTC/' /etc/php7/fpm/php.ini
    sed -i 's/;date.timezone =.*/date.timezone = UTC/' /etc/php7/cli/php.ini
    sed -i 's/;realpath_cache_size =.*/realpath_cache_size = 4096k/' /etc/php7/fpm/php.ini
    sed -i 's/;realpath_cache_ttl =.*/realpath_cache_ttl = 7200/' /etc/php7/fpm/php.ini

    cp sapi/fpm/php-fpm.conf.in /etc/php7/fpm/php-fpm.conf
    sed -i 's#^include=.*/#include=/etc/php7/fpm/pool.d/#' /etc/php7/fpm/php-fpm.conf

    mkdir /etc/php7/fpm/pool.d/
    cp /usr/local/php${PHP_VERSION}/etc/php-fpm.d/www.conf.default /etc/php7/fpm/pool.d/www.conf
    sed -i 's#^listen = 127.0.0.1:9000#listen = /var/run/php7-fpm.sock#' /etc/php7/fpm/pool.d/www.conf
    sed -i 's#^;listen.owner#listen.owner#' /etc/php7/fpm/pool.d/www.conf
    sed -i 's#^;listen.group#listen.group#' /etc/php7/fpm/pool.d/www.conf
    sed -i 's#^;listen.mode#listen.mode#' /etc/php7/fpm/pool.d/www.conf

    cp sapi/fpm/init.d.php-fpm /etc/init.d/php7-fpm
    chmod +x /etc/init.d/php7-fpm

    sed -i 's/Provides:          php-fpm/Provides:          php7-fpm/' /etc/init.d/php7-fpm 
    sed -i 's#^php_fpm_CONF=.*#php_fpm_CONF=/etc/php7/fpm/php-fpm.conf#' /etc/init.d/php7-fpm
    sed -i 's#^php_fpm_PID=.*#php_fpm_PID=/var/run/php7-fpm.pid#' /etc/init.d/php7-fpm

    update-rc.d php7-fpm defaults

    # Build extensions

    cd ..

    echo "export PATH=\"\$PATH:/usr/local/php${PHP_VERSION}/bin:/usr/local/php${PHP_VERSION}/sbin/\"" >> /etc/bash.bashrc

    # opcache
    echo "zend_extension=opcache.so" > /etc/php7/conf.d/opcache.ini
    ln -s /etc/php7/conf.d/opcache.ini /etc/php7/cli/conf.d/opcache.ini
    ln -s /etc/php7/conf.d/opcache.ini /etc/php7/fpm/conf.d/opcache.ini
}

function mysql_install {
    apt-get install -yqq mysql-server
    sed -i "s/^bind-address/#bind-address/" /etc/mysql/my.cnf
    echo "[mysqld]" > /etc/mysql/conf.d/utf8_charset.cnf
    echo "collation-server = utf8_unicode_ci" >> /etc/mysql/conf.d/utf8_charset.cnf
    echo "character-set-server = utf8" >> /etc/mysql/conf.d/utf8_charset.cnf
    service mysql restart
}

function nginx_install {
    apt-get install -y nginx
    ln -sf /vagrant/etc/server.conf /etc/nginx/sites-enabled/default
    sed -i 's#^user www-data#user vagrant#' /etc/nginx/nginx.conf
    service nginx restart
}

function elasticsearch_install {
    apt-get install -y openjdk-7-jdk elasticsearch
    service elasticsearch restart
}

function redis_install {
    apt-get install -y redis-server
    service redis-server restart
}

function varnish_install {
    apt-get install -y varnish
    ln -sf /vagrant/etc/default.vcl /etc/varnish/default.vcl
    sed -i 's#-a :6081#-a :80#' /lib/systemd/system/varnish.service
    systemctl daemon-reload
    systemctl restart varnish.service
}

function composer_install {
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
}

function app_install {
    wget -qO- https://dl.dropboxusercontent.com/u/16635543/images.tar.gz | tar xvz -C /vagrant/web/images
    cd /vagrant
    composer install --prefer-dist --optimize-autoloader
    php app/console doctrine:database:create
    php app/console doctrine:schema:create
    php app/console doctrine:fixtures:load
    php app/console fos:elastica:populate
}

pre_install
php_install
nginx_install
elasticsearch_install
redis_install
varnish_install
composer_install
mysql_install
app_install
post_install