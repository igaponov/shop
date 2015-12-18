<?php

namespace Shop\Application\Event;

use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\GetStatusInterface;

class RequestVerifiedEvent
{
    /**
     * @var GetStatusInterface
     */
    private $status;
    /**
     * @var PaymentInterface
     */
    private $payment;

    /**
     * @inheritDoc
     */
    public function __construct(GetStatusInterface $status, PaymentInterface $payment)
    {
        $this->status = $status;
        $this->payment = $payment;
    }

    /**
     * @return GetStatusInterface
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return PaymentInterface
     */
    public function getPayment()
    {
        return $this->payment;
    }
}