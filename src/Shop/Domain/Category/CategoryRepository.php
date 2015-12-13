<?php

namespace Shop\Domain\Category;

use Shop\Domain\ValueObject\UuidIdentity;

interface CategoryRepository
{
    /**
     * @param UuidIdentity $id
     * @return Category
     */
    public function findByIdentity(UuidIdentity $id) : Category;

    /**
     * @param Category $category
     */
    public function save(Category $category);

    /**
     * @param Category $category
     */
    public function remove(Category $category);

    /**
     * @param UuidIdentity $id
     * @return Category
     */
    public function getReference(UuidIdentity $id): Category;
}