<?php

namespace Shop\Application\Subscriber;

use Shop\Application\Event\RequestVerifiedEvent;
use Shop\Domain\Order\OrderViewRepository;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class PaymentEmailMessageSubscriber
{
    /**
     * @var OrderViewRepository
     */
    private $repository;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @inheritDoc
     */
    public function __construct(
        OrderViewRepository $repository,
        \Swift_Mailer $mailer,
        TranslatorInterface $translator,
        EngineInterface $engine
    )
    {
        $this->repository = $repository;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->engine = $engine;
    }

    public function notify(RequestVerifiedEvent $event)
    {
        $order = $this->repository->getById($event->getPayment()->getNumber());
        $message = \Swift_Message::newInstance(
            $this->translator->trans('message.email_subject'),
            $this->engine->render(':order:email.html.twig', ['order' => $order]),
            'html/text',
            'utf-8'
        );
        $this->mailer->send($message);
    }
}