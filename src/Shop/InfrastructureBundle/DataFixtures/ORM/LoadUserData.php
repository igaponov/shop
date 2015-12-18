<?php

namespace Shop\InfrastructureBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Shop\Domain\ValueObject\Address;
use Shop\Domain\ValueObject\UuidIdentity;
use Shop\InfrastructureBundle\Security\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $generator = $this->container->get('faker.generator');
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder(User::class);
        $salt = random_bytes(22);
        $admin = new User(
            new UuidIdentity(Uuid::uuid4()),
            'admin@mail.dev',
            $encoder->encodePassword('admin', $salt),
            $salt,
            User::ROLE_ADMIN,
            new Address(
                $generator->country,
                $generator->city,
                $generator->streetName,
                $generator->postcode
            )
        );
        $manager->persist($admin);
        $user = new User(
            new UuidIdentity(Uuid::uuid4()),
            'user@mail.dev',
            $encoder->encodePassword('user', $salt),
            $salt,
            User::ROLE_USER,
            new Address(
                $generator->country,
                $generator->city,
                $generator->streetName,
                $generator->postcode
            )
        );
        $manager->persist($user);
        $manager->flush();
    }
}