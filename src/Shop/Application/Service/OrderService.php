<?php

namespace Shop\Application\Service;

use Ramsey\Uuid\Uuid;
use Shop\Application\Command\CreateOrderCommand;
use Shop\Application\Event\OrderCreatedEvent;
use Shop\Application\Query\GetCustomerOrdersQuery;
use Shop\Application\Query\GetOrderByIdQuery;
use Shop\Domain\Cart\CartRepository;
use Shop\Domain\Order\Order;
use Shop\Domain\Order\OrderRepository;
use Shop\Domain\Order\OrderViewRepository;
use Shop\Domain\ValueObject\Address;
use Shop\Domain\ValueObject\UuidIdentity;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderService
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var OrderViewRepository
     */
    private $viewRepository;
    /**
     * @var CartRepository
     */
    private $cartRepository;
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * @inheritDoc
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        OrderRepository $orderRepository,
        OrderViewRepository $viewRepository,
        CartRepository $cartRepository,
        MessageBus $eventBus
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->orderRepository = $orderRepository;
        $this->viewRepository = $viewRepository;
        $this->cartRepository = $cartRepository;
        $this->eventBus = $eventBus;
    }

    public function createOrder(CreateOrderCommand $command)
    {
        $cart = $this->cartRepository->getCart();
        if ($cart->getLineItems()->count() === 0) {
            throw new \RuntimeException("The cart is empty");
        }
        $address = $command->getAddress();
        $order = new Order(
            new UuidIdentity(Uuid::uuid4()),
            $this->tokenStorage->getToken()->getUser(),
            new Address(
                $address->getCountry(),
                $address->getCity(),
                $address->getStreet(),
                $address->getZipCode()
            )
        );
        foreach ($cart->getLineItems() as $lineItem) {
            $order->addProduct($lineItem->getProduct(), $lineItem->getQuantity());
        }
        $this->orderRepository->save($order);

        $event = new OrderCreatedEvent($order, $command->getGatewayName());
        $this->eventBus->handle($event);
    }

    public function getCustomerOrders(GetCustomerOrdersQuery $query)
    {
        $result = $this->viewRepository->getByCustomer($query->getCustomerId());
        $query->setResult($result);
    }

    public function getOrderById(GetOrderByIdQuery $query)
    {
        $result = $this->viewRepository->getById($query->getId());
        $query->setResult($result);
    }
}