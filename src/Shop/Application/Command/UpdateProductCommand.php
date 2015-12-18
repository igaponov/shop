<?php

namespace Shop\Application\Command;

class UpdateProductCommand extends CreateProductCommand
{
    /**
     * @inheritDoc
     */
    public function __construct(
        \string $id,
        \string $name,
        \string $price,
        \string $category,
        \string $description,
        \bool $available,
        \string $imageUrl
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->description = $description;
        $this->available = $available;
        $this->imageUrl = $imageUrl;
    }
}