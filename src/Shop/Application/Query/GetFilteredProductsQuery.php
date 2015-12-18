<?php

namespace Shop\Application\Query;

class GetFilteredProductsQuery extends AbstractQuery
{
    /**
     * @var string
     */
    private $query;
    /**
     * @var string
     */
    private $category;
    /**
     * @var int
     */
    private $page;
    /**
     * @var int
     */
    private $priceFrom;
    /**
     * @var int
     */
    private $priceTo;
    /**
     * @var bool|null
     */
    private $availability = true;

    /**
     * @inheritDoc
     */
    public function __construct(\string $query = null, \string $category = null, int $page = 1)
    {
        $this->query = $query;
        $this->category = $category;
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * @param int $priceFrom
     */
    public function setPriceFrom($priceFrom)
    {
        $this->priceFrom = $priceFrom;
    }

    /**
     * @return int
     */
    public function getPriceTo()
    {
        return $this->priceTo;
    }

    /**
     * @param int $priceTo
     */
    public function setPriceTo($priceTo)
    {
        $this->priceTo = $priceTo;
    }

    /**
     * @return bool|null
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param bool|null $availability
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }
}