<?php

namespace Shop\InfrastructureBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
use Ramsey\Uuid\Uuid;
use Shop\Domain\ValueObject\Address;
use Shop\Domain\ValueObject\UuidIdentity;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthUserProvider extends EntityUserProvider
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $socialId = $response->getUsername();
        $user = $this->findUser([$this->getProperty($response) => $socialId]);
        $email = $response->getEmail();
        if (null === $user) {
            $user = $this->loadUserByUsername($email);
            if (null === $user || !$user instanceof UserInterface) {
                $user = new User(
                    new UuidIdentity(Uuid::uuid4()),
                    $email,
                    md5(uniqid()),
                    random_bytes(),
                    User::ROLE_USER,
                    new Address()
                );
            }
            $service = $response->getResourceOwner()->getName();
            switch ($service) {
                case 'google':
                    $user->setGoogleID($socialId);
                    break;
                case 'facebook':
                    $user->setFacebookID($socialId);
                    break;
            }
        }

        return $user;
    }

    /**
     * Gets the property for the response.
     *
     * @param UserResponseInterface $response
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getProperty(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        return $this->properties[$resourceOwnerName];
    }
}