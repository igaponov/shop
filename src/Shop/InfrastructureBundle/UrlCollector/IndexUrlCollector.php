<?php

namespace Shop\InfrastructureBundle\UrlCollector;

use Symfony\Component\Routing\RouterInterface;

class IndexUrlCollector implements UrlCollectorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var array
     */
    private $locales;

    /**
     * @inheritDoc
     */
    public function __construct(RouterInterface $router, \string $locales)
    {
        $this->router = $router;
        $this->locales = explode('|', $locales);
    }

    /**
     * @inheritDoc
     */
    public function collect(): \Iterator
    {
        $iterator = new \ArrayIterator();
        $routes = ['homepage', 'products_filter', 'products', 'cart', 'profile'];
        foreach ($this->locales as $locale) {
            foreach ($routes as $route) {
                $iterator->append($this->router->generate($route, ['_locale' => $locale], RouterInterface::ABSOLUTE_URL));
            }
        }

        return $iterator;
    }
}