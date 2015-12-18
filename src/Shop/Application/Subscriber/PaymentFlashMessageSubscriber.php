<?php

namespace Shop\Application\Subscriber;

use Payum\Core\Request\GetHumanStatus;
use Shop\Application\Event\RequestVerifiedEvent;
use Shop\Domain\Cart\CartRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class PaymentFlashMessageSubscriber
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var CartRepository
     */
    private $repository;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @inheritDoc
     */
    public function __construct(
        Session $session,
        CartRepository $repository,
        TranslatorInterface $translator
    ) {
        $this->session = $session;
        $this->repository = $repository;
        $this->translator = $translator;
    }

    public function notify(RequestVerifiedEvent $event)
    {
        $payment = $event->getPayment();
        $status = $event->getStatus()->getValue();
        switch ($status) {
            case GetHumanStatus::STATUS_AUTHORIZED:
            case GetHumanStatus::STATUS_CAPTURED:
            case GetHumanStatus::STATUS_REFUNDED:
                $this->repository->clearCart();
                $type = 'success';
                break;
            case GetHumanStatus::STATUS_CANCELED:
            case GetHumanStatus::STATUS_EXPIRED:
            case GetHumanStatus::STATUS_FAILED:
                $type = 'danger';
                break;
            case GetHumanStatus::STATUS_PENDING:
            case GetHumanStatus::STATUS_SUSPENDED:
                $this->repository->clearCart();
                $type = 'warning';
                break;
            case GetHumanStatus::STATUS_NEW:
            case GetHumanStatus::STATUS_UNKNOWN:
                $this->repository->clearCart();
                $type = 'info';
                break;
            default:
                throw new \RuntimeException('Unknown status '.$status);
        }
        $formatter = new \NumberFormatter($this->translator->getLocale(), \NumberFormatter::CURRENCY);
        $this->session->getFlashBag()->add(
            $type,
            $this->translator->trans(
                'flash.payment.'.$type,
                [
                    '%status%' => $this->translator->trans('meta.status.'.$status),
                    '%amount%' => $formatter->formatCurrency(
                        $payment->getTotalAmount() / 100,
                        $payment->getCurrencyCode()
                    ),
                ]
            )
        );
    }
}