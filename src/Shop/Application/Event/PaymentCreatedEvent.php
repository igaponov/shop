<?php

namespace Shop\Application\Event;

use Payum\Core\Security\TokenInterface;

class PaymentCreatedEvent
{
    /**
     * @var TokenInterface
     */
    private $token;

    /**
     * @inheritDoc
     */
    public function __construct(TokenInterface $token)
    {
        $this->token = $token;
    }

    /**
     * @return TokenInterface
     */
    public function getToken()
    {
        return $this->token;
    }
}