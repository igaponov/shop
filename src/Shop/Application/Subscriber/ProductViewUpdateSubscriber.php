<?php

namespace Shop\Application\Subscriber;

use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Shop\Application\Event\ProductSavedEvent;
use Shop\Domain\Product\ProductViewRepository;

class ProductViewUpdateSubscriber
{
    /**
     * @var ObjectPersisterInterface
     */
    private $persister;
    /**
     * @var ProductViewRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function __construct(
        ObjectPersisterInterface $persister,
        ProductViewRepository $repository
    ) {
        $this->persister = $persister;
        $this->repository = $repository;
    }

    public function onProductSave(ProductSavedEvent $event)
    {
        $product = $event->getProduct();
        $this->repository->saveAvailability($product->getId()->getValue(), $product->isAvailable());
        if ($this->persister->handlesObject($product)) {
            $this->persister->insertOne($product);
        }
    }
}