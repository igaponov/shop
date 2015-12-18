<?php

namespace Shop\InfrastructureBundle\Collection;

use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Shop\Domain\Cart\LineItemCollection;
use Shop\Domain\Cart\LineItemView;
use Shop\Domain\Product\ProductViewRepository;

class LineItemLazyCollection extends AbstractLazyCollection implements LineItemCollection
{
    /**
     * @var ProductViewRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function __construct(array $lineItems, ProductViewRepository $repository)
    {
        $this->collection = new ArrayCollection($lineItems);
        $this->repository = $repository;
    }

    /**
     * Do the initialization logic
     *
     * @return void
     */
    protected function doInitialize()
    {
        $collection = $this->collection;
        $ids = $collection->getKeys();
        $products = $this->repository->findByIds($ids);
        $this->collection = new ArrayCollection();
        foreach ($products as $product) {
            $lineItem = LineItemView::create($product, $collection->get($product->getId()));
            $this->collection->set($product->getId(), $lineItem);
        }
    }

    /**
     * @inheritDoc
     */
    public function getProductCount()
    {
        $total = 0;
        if ($this->isInitialized()) {
            $this->map(function(LineItemView $lineItem) use (&$total) {
                $total += $lineItem->getQuantity();
            });
        } else {
            $total = array_sum($this->collection->getValues());
        }

        return $total;
    }
}