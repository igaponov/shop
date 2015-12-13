<?php

namespace Shop\Domain\ValueObject;

class UuidIdentity
{
    /**
     * @var string
     */
    private $value;

    public function __construct(\string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): \string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->getValue();
    }
}
