<?php

namespace Shop\Presentation\Controller;

use Shop\Application\Command\VerifyRequestCommand;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PaymentController
{
    /**
     * @var MessageBus
     */
    private $commandBus;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @inheritDoc
     */
    public function __construct(
        MessageBus $commandBus,
        RouterInterface $router
    )
    {
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function doneAction(Request $request)
    {
        $command = new VerifyRequestCommand($request);
        $this->commandBus->handle($command);

        return new RedirectResponse($this->router->generate('homepage'));
    }
}