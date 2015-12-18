<?php

namespace Shop\InfrastructureBundle\Security;

use Shop\Domain\Order\OrderView;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OrderVoter extends Voter
{
    /**
     * @param string $attribute
     * @param OrderView $subject
     * @param TokenInterface $token
     * @return bool|void
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if ($user instanceof User) {
            return $subject->getCustomerId() === $user->getId()->getValue();
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        return $subject instanceof OrderView;
    }
}