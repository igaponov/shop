<?php

namespace Shop\InfrastructureBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Shop\Domain\Option\Option;
use Shop\Domain\ValueObject\UuidIdentity;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadOptionData extends AbstractFixture implements ContainerAwareInterface
{
    const COUNT = 100;

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
        for ($i = 1; $i <= self::COUNT; $i++) {
            $option = new Option(
                new UuidIdentity(Uuid::uuid4()),
                ucfirst($generator->words(2, true))
            );
            $manager->persist($option);
            $this->addReference('option_' . $i, $option);
        }
        $manager->flush();
    }
}