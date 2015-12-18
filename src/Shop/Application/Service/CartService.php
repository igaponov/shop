<?php

namespace Shop\Application\Service;

use Shop\Application\Command\AddProductCommand;
use Shop\Application\Command\RemoveProductCommand;
use Shop\Application\Query\GetCartQuery;
use Shop\Domain\Cart\CartRepository;
use Shop\Domain\Cart\CartViewRepository;
use Shop\Domain\Product\ProductRepository;
use Shop\Domain\Product\ProductViewRepository;
use Shop\Domain\ValueObject\Quantity;
use Shop\Domain\ValueObject\UuidIdentity;

class CartService
{
    /**
     * @var CartRepository
     */
    private $repository;
    /**
     * @var CartViewRepository
     */
    private $viewRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ProductViewRepository
     */
    private $productViewRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        CartRepository $repository,
        CartViewRepository $viewRepository,
        ProductRepository $productRepository,
        ProductViewRepository $productViewRepository
    )
    {
        $this->repository = $repository;
        $this->viewRepository = $viewRepository;
        $this->productRepository = $productRepository;
        $this->productViewRepository = $productViewRepository;
    }

    public function addProduct(AddProductCommand $command)
    {
        $cart = $this->repository->getCart();
        $product = $this->productRepository->findByIdentity(new UuidIdentity($command->getProductId()));
        $cart->addProduct(
            $product,
            new Quantity($command->getQuantity())
        );
        $this->repository->setCart($cart);
    }

    public function removeProduct(RemoveProductCommand $command)
    {
        $cart = $this->repository->getCart();
        $cart->removeProduct(new UuidIdentity($command->getId()));
        $this->repository->setCart($cart);
    }

    public function getCart(GetCartQuery $query)
    {
        $cart = $this->viewRepository->getCart();
        $query->setResult($cart);
    }
}