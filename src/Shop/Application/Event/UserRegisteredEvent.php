<?php

namespace Shop\Application\Event;

use Shop\InfrastructureBundle\Security\User;

class UserRegisteredEvent
{
    /**
     * @var User
     */
    private $user;

    /**
     * @inheritDoc
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}