<?php

namespace Shop\Presentation\Controller;

use Shop\Application\Command\UpdateProfileCommand;
use Shop\Application\Model\Address;
use Shop\Application\Query\GetActualCustomerQuery;
use Shop\Application\Query\GetCartQuery;
use Shop\Application\Query\GetCustomerOrdersQuery;
use Shop\Domain\Customer\CustomerView;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class ProfileController
{
    /**
     * @var MessageBus
     */
    private $queryBus;
    /**
     * @var MessageBus
     */
    private $commandBus;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @inheritDoc
     */
    public function __construct(
        MessageBus $queryBus,
        MessageBus $commandBus,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        EngineInterface $engine
    ) {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->engine = $engine;
    }

    public function indexAction()
    {
        $response = new Response($this->engine->render(':profile:index.html.twig'));
        $response->setSharedMaxAge(3600);

        return $response;
    }

    public function profileAction(Request $request)
    {
        $query = new GetActualCustomerQuery();
        $this->queryBus->handle($query);
        /** @var CustomerView $customer */
        $customer = $query->getResult();

        $form = $this->createForm($customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $command = $form->getData();
            $this->commandBus->handle($command);

            return new RedirectResponse($this->router->generate('profile'));
        }

        $query = new GetCustomerOrdersQuery($customer->getId());
        $this->queryBus->handle($query);
        $orders = $query->getResult();

        return new Response(
            $this->engine->render(
                ':profile:profile.html.twig',
                [
                    'user' => $customer,
                    'orders' => $orders,
                    'form' => $form->createView(),
                ]
            )
        );
    }

    public function navAction()
    {
        $query = new GetCartQuery();
        $this->queryBus->handle($query);

        return new Response(
            $this->engine->render(
                ':profile:nav.html.twig',
                ['cart' => $query->getResult()]
            )
        );
    }

    /**
     * @param CustomerView $customer
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createForm($customer)
    {
        $form = $this->formFactory->create(
            'Shop\Presentation\Form\ProfileType',
            new UpdateProfileCommand(
                new Address(
                    $customer->getCountry(),
                    $customer->getCity(),
                    $customer->getStreet(),
                    $customer->getZipCode()
                )
            )
        );

        return $form;
    }
}