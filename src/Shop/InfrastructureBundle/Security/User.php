<?php

namespace Shop\InfrastructureBundle\Security;

use Shop\Domain\ValueObject\Address;
use Shop\Domain\Customer\CustomerInterface;
use Shop\Domain\ValueObject\UuidIdentity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements CustomerInterface, UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    const ROLE_USER = 'ROLE_USER';

    /**
     * @var UuidIdentity
     */
    private $id;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $salt;
    /**
     * @var Role
     */
    private $role;
    /**
     * @var Address
     */
    private $address;

    public function __construct(
        UuidIdentity $id,
        \string $username,
        \string $password,
        \string $salt,
        \string $role,
        Address $address
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->role = $role;
        $this->address = $address;
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
    public function getEmail()
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return $this
     */
    public function changeAddress(Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
    }

    public function setU($u)
    {
        $this->username = $u;
    }
}
