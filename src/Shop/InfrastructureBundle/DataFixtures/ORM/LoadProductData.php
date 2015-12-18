<?php

namespace Shop\InfrastructureBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Shop\Domain\Category\Category;
use Shop\Domain\Option\Option;
use Shop\Domain\Product\Product;
use Shop\Domain\ValueObject\Money;
use Shop\Domain\ValueObject\UuidIdentity;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProductData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        /** @var \FilesystemIterator $iterator */
        $dir = $this->container->getParameter('kernel.root_dir').'/../web/images/';
        $iterator = new \InfiniteIterator(new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS));
        for($i = 0; $i < 200; $i++) {
            /** @var Category $category */
            $category = $this->getReference('category_'.mt_rand(1, LoadCategoryData::COUNT));
            $product = new Product(
                new UuidIdentity(Uuid::uuid4()),
                $generator->company . ' ' . $generator->words(2, true),
                new Money($generator->randomNumber(4)),
                $category,
                $generator->text(500),
                $generator->boolean(90),
                '/images/'.$iterator->getFilename()
            );
            $iterator->next();
            $opts = array_rand(range(1, LoadOptionData::COUNT), mt_rand(2, 5));
            foreach ($opts as $opt) {
                /** @var Option $option */
                $option = $this->getReference('option_'.($opt + 1));
                $product->addOption(
                    $option,
                    $generator->words(2, true)
                );
            }
            $manager->persist($product);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            LoadCategoryData::class,
            LoadOptionData::class
        ];
    }
}