<?php

namespace Shop\Domain\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Shop\Domain\Category\Category;
use Shop\Domain\Option\Option;
use Shop\Domain\ValueObject\Money;
use Shop\Domain\ValueObject\UuidIdentity;

class Product
{
    /**
     * @var UuidIdentity
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Money
     */
    private $price;
    /**
     * @var Category
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
     * @var \DateTime
     */
    private $updated;
    /**
     * @var Collection
     */
    private $productOptions;

    public function __construct(
        UuidIdentity $id,
        \string $name,
        Money $price,
        Category $category,
        \string $description,
        \bool $availability,
        \string $imageUrl
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->description = $description;
        $this->availability = $availability;
        $this->imageUrl = $imageUrl;
        $this->updated = new \DateTime();
        $this->productOptions = new ArrayCollection();
    }

    /**
     * @return UuidIdentity
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
     * @return Money
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return Category
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
     * @param string $name
     * @param Money $price
     * @param Category $category
     * @param string $description
     * @param bool $availability
     * @param string $imageUrl
     */
    public function updateInfo(
        $name,
        Money $price,
        Category $category,
        $description,
        \bool $availability,
        $imageUrl
    )
    {
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->description = $description;
        $this->availability = $availability;
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return Collection|ProductOption[]
     */
    public function getProductOptions()
    {
        return $this->productOptions;
    }

    /**
     * @param Option $option
     * @param $value
     * @return $this
     */
    public function addOption(Option $option, $value)
    {
        $productOption = new ProductOption($this, $option, (string) $value);
        $this->productOptions->add($productOption);

        return $this;
    }

    /**
     * @param Option $option
     * @return $this
     */
    public function deleteOption(Option $option)
    {
        $productOptions = $this->productOptions->filter(function(ProductOption $productOption) use($option) {
            return $productOption->getOption()->getId() == $option->getId();
        });

        if ($productOptions->count()) {
            $this->productOptions->removeElement($productOptions->first());
        }

        return $this;
    }
}
