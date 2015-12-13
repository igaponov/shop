<?php

namespace Shop\Domain\ValueObject;

class Money
{
    /**
     * @var int
     */
    private $amount;

    /**
     * Create a Money instance
     * @param  integer $amount    Amount, expressed in the smallest units
     */
    public function __construct(\int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param Money $addend
     *@return Money
     */
    public function add(Money $addend)
    {
        return new self($this->amount + $addend->amount);
    }

    /**
     * @param Money $subtrahend
     * @return Money
     */
    public function subtract(Money $subtrahend)
    {
        return new self($this->amount - $subtrahend->amount);
    }
}