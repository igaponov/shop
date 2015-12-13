<?php

namespace Shop\Domain\Product;

use Pagerfanta\Pagerfanta;

interface ProductViewRepository
{
    /**
     * @param string|null $queryString
     * @param string|null $category
     * @param int|null $priceFrom
     * @param int|null $priceTo
     * @param bool|null $availability
     * @return Pagerfanta
     */
    public function findPaginatedByQueryAndCategory(
        \string $queryString = null,
        \string $category = null,
        \int $priceFrom = null,
        \int $priceTo = null,
        \bool $availability = null
    );

    /**
     * @param string $id
     * @return ProductView
     */
    public function findById(\string $id) : ProductView;

    /**
     * @param array $ids
     * @return ProductView[]
     */
    public function findByIds(array $ids): array;

    /**
     * @param string $id
     * @return bool
     */
    public function getAvailability(\string $id): \bool;

    /**
     * @param string $id
     * @param bool $availability
     * @return void
     */
    public function saveAvailability(\string $id, \bool $availability);

    /**
     * @param mixed $query
     * @param array $options
     * @return Pagerfanta
     */
    public function findPaginated($query, $options = []);

    /**
     * @return \Iterator
     */
    public function findAllIds();
}