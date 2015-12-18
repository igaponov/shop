<?php

namespace Shop\Application\Service;

use Ramsey\Uuid\Uuid;
use Shop\Application\Command\CreateProductCommand;
use Shop\Application\Command\UpdateProductCommand;
use Shop\Application\Event\ProductSavedEvent;
use Shop\Application\Query\GetFilteredProductsQuery;
use Shop\Application\Query\GetProductAvailabilityByIdQuery;
use Shop\Application\Query\GetProductByIdQuery;
use Shop\Application\Query\GetProductsByIdsQuery;
use Shop\Domain\Category\CategoryRepository;
use Shop\Domain\Option\OptionRepository;
use Shop\Domain\Product\Product;
use Shop\Domain\Product\ProductRepository;
use Shop\Domain\Product\ProductViewRepository;
use Shop\Domain\ValueObject\Money;
use Shop\Domain\ValueObject\UuidIdentity;
use SimpleBus\Message\Bus\MessageBus;

class ProductService
{
    const LIMIT = 12;

    /**
     * @var ProductRepository
     */
    private $repository;
    /**
     * @var ProductViewRepository
     */
    private $viewRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var OptionRepository
     */
    private $optionRepository;
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * @inheritDoc
     */
    public function __construct(
        ProductRepository $repository,
        ProductViewRepository $viewRepository,
        CategoryRepository $categoryRepository,
        OptionRepository $optionRepository,
        MessageBus $eventBus
    ) {
        $this->repository = $repository;
        $this->viewRepository = $viewRepository;
        $this->categoryRepository = $categoryRepository;
        $this->optionRepository = $optionRepository;
        $this->eventBus = $eventBus;
    }

    public function getFilteredProducts(GetFilteredProductsQuery $query)
    {
        $pager = $this->viewRepository->findPaginatedByQueryAndCategory(
            $query->getQuery(),
            $query->getCategory(),
            $query->getPriceFrom(),
            $query->getPriceTo(),
            $query->getAvailability()
        );
        $pager->setMaxPerPage(self::LIMIT);
        $pager->setCurrentPage($query->getPage());
        $query->setResult($pager);
    }

    public function getProductById(GetProductByIdQuery $query)
    {
        $result = $this->viewRepository->findById($query->getId());
        $query->setResult($result);
    }

    public function getProductAvailabilityById(GetProductAvailabilityByIdQuery $query)
    {
        $result = $this->viewRepository->getAvailability($query->getId());
        $query->setResult($result);
    }

    public function createProduct(CreateProductCommand $command)
    {
        $uuid = Uuid::uuid4();
        $command->setId($uuid);
        $category = $this->categoryRepository->findByIdentity(new UuidIdentity($command->getCategory()));
        $product = new Product(
            new UuidIdentity($command->getId()),
            $command->getName(),
            new Money($command->getPrice()),
            $category,
            $command->getDescription(),
            $command->isAvailable(),
            $command->getImageUrl()
        );
        foreach ($command->getProductOptions() as $productOption) {
            $option = $this->optionRepository->getReference(new UuidIdentity($productOption->getOption()));
            $product->addOption($option, $productOption->getValue());
        }
        $this->repository->save($product);

        $event = new ProductSavedEvent($product);
        $this->eventBus->handle($event);
    }

    public function updateProduct(UpdateProductCommand $command)
    {
        $product = $this->repository->findByIdentity(new UuidIdentity($command->getId()));
        $category = $this->categoryRepository->findByIdentity(new UuidIdentity($command->getCategory()));
        $product->updateInfo(
            $command->getName(),
            new Money($command->getPrice()),
            $category,
            $command->getDescription(),
            $command->isAvailable(),
            $command->getImageUrl()
        );
        $productOptions = $command->getProductOptions();
        foreach ($product->getProductOptions() as $key => $productOption) {
            if (isset($productOptions[$key])) {
                $productOption->changeValue($productOptions[$key]->getValue());
                unset($productOptions[$key]);
            } else {
                $product->getProductOptions()->remove($key);
            }
        }
        foreach ($productOptions as $productOption) {
            $option = $this->optionRepository->getReference(new UuidIdentity($productOption->getOption()));
            $product->addOption($option, $productOption->getValue());
        }
        $this->repository->save($product);

        $event = new ProductSavedEvent($product);
        $this->eventBus->handle($event);
    }
}