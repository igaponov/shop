# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.provider "virtualbox" do |v|
    v.memory = 1024
    v.cpus = 2
  end
  config.vm.box = "debian/jessie64"
  config.vm.network "private_network", ip: "10.40.0.30"
  config.vm.provision :shell, :path => "provision.sh"
  config.vm.synced_folder ".", "/vagrant", type: "nfs"
end
