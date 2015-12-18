<?php

namespace Shop\Application\Subscriber;

use Shop\Application\Event\OrderCreatedEvent;
use Shop\Application\Factory\PaymentTokenFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CaptureOrderSubscriber
{
    /**
     * @var PaymentTokenFactory
     */
    private $tokenFactory;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @inheritDoc
     */
    public function __construct(
        PaymentTokenFactory $tokenFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tokenFactory = $tokenFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function notify(OrderCreatedEvent $event)
    {
        $captureToken = $this->tokenFactory->createToken($event->getOrder(), $event->getGatewayName());
        $this->eventDispatcher->addListener(
            KernelEvents::RESPONSE,
            function (FilterResponseEvent $event) use ($captureToken) {
                $event->setResponse(new RedirectResponse($captureToken->getTargetUrl()));
            },
            -1000
        );
    }
}