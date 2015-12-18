<?php

namespace Shop\Presentation\Controller;

use Shop\Application\Command\CreateOrderCommand;
use Shop\Application\Command\RemoveProductCommand;
use Shop\Application\Model\Address;
use Shop\Application\Query\GetActualCustomerQuery;
use Shop\Application\Query\GetCartQuery;
use Shop\Domain\Customer\CustomerView;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Templating\EngineInterface;

class CartController
{
    /**
     * @var EngineInterface
     */
    private $engine;
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
     * @inheritDoc
     */
    public function __construct(
        EngineInterface $engine,
        MessageBus $queryBus,
        MessageBus $commandBus,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->engine = $engine;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function indexAction()
    {
        $response = new Response($this->engine->render(':cart:index.html.twig'));
        $response->setSharedMaxAge(3600);

        return $response;
    }

    public function cartAction(Request $request)
    {
        $query = new GetCartQuery();
        $this->queryBus->handle($query);
        $cart = $query->getResult();

        try {
            $query = new GetActualCustomerQuery();
            $this->queryBus->handle($query);
            /** @var CustomerView $customer */
            $customer = $query->getResult();
            $form = $this->getOrderForm($customer);
        } catch (AccessDeniedException $e) {
            $form = $this->getUserForm();
        }

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $command = $form->getData();
            $this->commandBus->handle($command);
        }

        return new Response(
            $this->engine->render(
                ':cart:cart.html.twig',
                ['cart' => $cart, 'form' => $form->createView()]
            )
        );
    }

    public function addProductAction(Request $request)
    {
        $form = $this->formFactory->createNamed('', 'Shop\Presentation\Form\AddProductType');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $command = $form->getData();
            $this->commandBus->handle($command);
            if ($request->isXmlHttpRequest()) {
                $response = new Response('', Response::HTTP_CREATED);
            } else {
                $response = new RedirectResponse($this->router->generate('cart'));
            }
        } else {
            $response = new Response($form->getErrors()->current()->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    public function removeProductAction($id)
    {
        $command = new RemoveProductCommand($id);
        $this->commandBus->handle($command);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param CustomerView $customer
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getOrderForm(CustomerView $customer)
    {
        return $form = $this->formFactory->create(
            'Shop\Presentation\Form\OrderType',
            new CreateOrderCommand(
                new Address(
                    $customer->getCountry(),
                    $customer->getCity(),
                    $customer->getStreet(),
                    $customer->getZipCode()
                )
            )
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getUserForm()
    {
        $form = $this->formFactory->createNamed(
            '',
            'Shop\Presentation\Form\LoginType',
            ['_target_path' => $this->router->generate('cart')],
            ['action' => $this->router->generate('login_check')]
        );

        return $form;
    }
}