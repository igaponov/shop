<?php

namespace Shop\Domain\Category;

use Shop\Domain\ValueObject\UuidIdentity;

class Category
{
    /**
     * @var UuidIdentity
     */
    private $id;
    /**
     * @var string
     */
    private $name;

    public function __construct(UuidIdentity $id, \string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
     * @param string $name
     */
    public function rename($name)
    {
        $this->name = $name;
    }
}
