<?php

namespace Shop\Domain\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Shop\Domain\ValueObject\UuidIdentity;

interface ProductRepository
{
    const LIMIT = 10;

    /**
     * @param UuidIdentity $id
     * @return Product
     */
    public function findByIdentity(UuidIdentity $id) : Product;

    /**
     * @param array|UuidIdentity[] $ids
     * @return ArrayCollection|Product[]
     */
    public function findByIdentities(array $ids);

    /**
     * @param Product $product
     * @return null
     */
    public function save(Product $product);

    /**
     * @param Product $product
     * @return null
     */
    public function remove(Product $product);
}