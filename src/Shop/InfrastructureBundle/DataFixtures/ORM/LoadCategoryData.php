<?php

namespace Shop\InfrastructureBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Shop\Domain\Category\Category;
use Shop\Domain\ValueObject\UuidIdentity;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCategoryData extends AbstractFixture implements ContainerAwareInterface
{
    const COUNT = 15;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $generator = $this->container->get('faker.generator');
        for($i = 1; $i <= self::COUNT; $i++) {
            $category = new Category(
                new UuidIdentity(Uuid::uuid4()->toString()),
                ucfirst($generator->words(2, true))
            );
            $manager->persist($category);
            $this->addReference('category_' . $i, $category);
        }
        $manager->flush();
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}