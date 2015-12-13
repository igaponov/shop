<?php

namespace Shop\Domain\Option;

use Shop\Domain\ValueObject\UuidIdentity;

class Option
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

}
