<?php

namespace Shop\Application\Subscriber;

use Payum\Core\Request\GetHumanStatus;
use Shop\Application\Event\RequestVerifiedEvent;
use Shop\Domain\Order\OrderRepository;
use Shop\Domain\ValueObject\UuidIdentity;

class MarkOrderAsPaidSubscriber
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @inheritDoc
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function notify(RequestVerifiedEvent $event)
    {
        $payment = $event->getPayment();
        $status = $event->getStatus()->getValue();
        if (in_array(
            $status,
            [
                GetHumanStatus::STATUS_AUTHORIZED,
                GetHumanStatus::STATUS_CAPTURED,
                GetHumanStatus::STATUS_REFUNDED,
            ]
        )) {
            $order = $this->orderRepository->findByIdentity(
                new UuidIdentity($payment->getNumber())
            );
            $order->markAsPaid();
            $this->orderRepository->save($order);
        }
    }
}