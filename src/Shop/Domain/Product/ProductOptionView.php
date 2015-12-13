<?php

namespace Shop\Domain\Product;

use Shop\Domain\Option\OptionView;

class ProductOptionView
{
    /**
     * @var OptionView
     */
    private $option;
    /**
     * @var string
     */
    private $value;

    public static function create(OptionView $option, string $value)
    {
        $object = new static;
        $object->option = $option;
        $object->value = $value;

        return $object;
    }

    /**
     * @return OptionView
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
}