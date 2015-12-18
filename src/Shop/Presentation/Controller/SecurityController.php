<?php

namespace Shop\Presentation\Controller;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Templating\EngineInterface;

class SecurityController
{
    /**
     * @var AuthenticationUtils
     */
    private $utils;
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
        AuthenticationUtils $utils,
        MessageBus $commandBus,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        EngineInterface $engine)
    {
        $this->utils = $utils;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->engine = $engine;
    }

    public function loginAction()
    {
        $form = $this->formFactory->createNamed(
            '',
            'Shop\Presentation\Form\LoginType',
            ['_username' => $this->utils->getLastUsername()],
            [
                'action' => $this->router->generate('login_check')
            ]
        );
        $error = $this->utils->getLastAuthenticationError();

        return new Response(
            $this->engine->render(
                ':security:login.html.twig',
                [
                    'form' => $form->createView(),
                    'error' => $error,
                ]
            )
        );
    }

    public function registerAction(Request $request)
    {
        $form = $this->formFactory->create('Shop\Presentation\Form\UserType');
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $command = $form->getData();
            $this->commandBus->handle($command);

            return new RedirectResponse('/');
        }

        return new Response($this->engine->render(
            ':security:register.html.twig',
            ['form' => $form->createView()]
        ));
    }

//    public function loginCheckAction()
//    {
        // this controller will not be executed,
        // as the route is handled by the Security system
//    }
}