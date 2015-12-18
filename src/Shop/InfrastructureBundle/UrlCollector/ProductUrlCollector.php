<?php

namespace Shop\InfrastructureBundle\UrlCollector;

use Shop\Domain\Product\ProductViewRepository;
use Symfony\Component\Routing\RouterInterface;

class ProductUrlCollector implements UrlCollectorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var ProductViewRepository
     */
    private $repository;
    /**
     * @var array
     */
    private $locales;

    /**
     * @inheritDoc
     */
    public function __construct(
        RouterInterface $router,
        ProductViewRepository $repository,
        \string $locales
    ) {
        $this->router = $router;
        $this->repository = $repository;
        $this->locales = explode('|', $locales);
    }

    /**
     * @inheritDoc
     */
    public function collect(): \Iterator
    {
        $generator = function () {
            foreach ($this->repository->findAllIds() as $id) {
                foreach ($this->locales as $locale) {
                    yield $this->router->generate('product', ['id' => $id, '_locale' => $locale], RouterInterface::ABSOLUTE_URL);
                }
            }
        };

        return $generator();
    }
}