<?php

namespace Shop\Application\Service;

use Payum\Bundle\PayumBundle\Registry\ContainerAwareRegistry;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Security\HttpRequestVerifierInterface;
use Shop\Application\Command\VerifyRequestCommand;
use Shop\Application\Event\RequestVerifiedEvent;
use SimpleBus\Message\Bus\MessageBus;

class PaymentService
{
    /**
     * @var ContainerAwareRegistry
     */
    private $registry;
    /**
     * @var HttpRequestVerifierInterface
     */
    private $requestVerifier;
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * @inheritDoc
     */
    public function __construct(
        ContainerAwareRegistry $registry,
        HttpRequestVerifierInterface $requestVerifier,
        MessageBus $eventBus
    ) {
        $this->registry = $registry;
        $this->requestVerifier = $requestVerifier;
        $this->eventBus = $eventBus;
    }

    public function verifyRequest(VerifyRequestCommand $command)
    {
        $token = $this->requestVerifier->verify($command->getRequest());
        $gateway = $this->registry->getGateway($token->getGatewayName());
        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();
        $this->requestVerifier->invalidate($token);

        $event = new RequestVerifiedEvent($status, $payment);
        $this->eventBus->handle($event);
    }
}