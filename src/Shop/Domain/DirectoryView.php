<?php

namespace Shop\Domain;

class DirectoryView
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $productCount;

    /**
     * @param string $id
     * @param string $name
     * @param int $productCount
     * @return $this
     */
    public static function create(\string $id, \string $name, \int $productCount = 0)
    {
        $object = new static;
        $object->id = $id;
        $object->name = $name;
        $object->productCount = $productCount;

        return $object;
    }

    /**
     * @return string
     */
    public function getId(): \string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): \string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getProductCount(): \int
    {
        return $this->productCount;
    }
}