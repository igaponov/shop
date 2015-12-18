<?php

namespace Shop\Presentation\Controller;

use Shop\Application\Query\GetOrderByIdQuery;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Templating\EngineInterface;

class OrderController
{
    /**
     * @var MessageBus
     */
    private $queryBus;
    /**
     * @var EngineInterface
     */
    private $engine;
    /**
     * @var AuthorizationChecker
     */
    private $checker;

    /**
     * @inheritDoc
     */
    public function __construct(
        MessageBus $queryBus,
        EngineInterface $engine,
        AuthorizationChecker $checker
    )
    {
        $this->engine = $engine;
        $this->queryBus = $queryBus;
        $this->checker = $checker;
    }

    public function viewAction($id)
    {
        $response = new Response(
            $this->engine->render(':order:view.html.twig', ['id' => $id])
        );
        $response->setSharedMaxAge(3600);

        return $response;
    }

    public function orderAction($id)
    {
        $query = new GetOrderByIdQuery($id);
        $this->queryBus->handle($query);

        if (!$this->checker->isGranted('read', $query->getResult())) {
            throw new AccessDeniedException;
        }

        return new Response(
            $this->engine->render(':order:order.html.twig', ['order' => $query->getResult()])
        );
    }
}