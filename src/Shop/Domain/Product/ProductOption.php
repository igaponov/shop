<?php

namespace Shop\Domain\Product;

use Shop\Domain\Option\Option;

class ProductOption
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var Option
     */
    private $option;
    /**
     * @var string
     */
    private $value;

    public function __construct(Product $product, Option $option, $value)
    {
        $this->product = $product;
        $this->option = $option;
        $this->value = $value;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return Option
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function changeValue($value)
    {
        $this->value = $value;
    }
}
