<?php

namespace Shop\Domain\Product;

use Shop\Domain\Category\CategoryView;

class ProductView
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $price;
    /**
     * @var CategoryView
     */
    private $category;
    /**
     * @var string
     */
    private $description;
    /**
     * @var bool
     */
    private $availability;
    /**
     * @var string
     */
    private $imageUrl;
    /**
     * @var string
     */
    private $updated;
    /**
     * @var array|ProductOptionView[]
     */
    private $productOptions;

    public static function create(
        \string $id,
        \string $name,
        \string $price,
        CategoryView $category,
        \string $description,
        \bool $availability,
        \string $imageUrl,
        \string $updated,
        array $productOptions = []
    ) {
        $object = new static;
        $object->id = $id;
        $object->name = $name;
        $object->price = $price;
        $object->category = $category;
        $object->description = $description;
        $object->availability = $availability;
        $object->imageUrl = $imageUrl;
        $object->updated = $updated;
        $object->productOptions = $productOptions;

        return $object;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return CategoryView
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return $this->availability;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return ProductOptionView[]
     */
    public function getProductOptions()
    {
        return $this->productOptions;
    }
}