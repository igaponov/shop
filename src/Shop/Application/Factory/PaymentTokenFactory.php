<?php

namespace Shop\Application\Factory;

use Payum\Bundle\PayumBundle\Registry\ContainerAwareRegistry;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Core\Security\TokenInterface;
use Shop\Domain\Order\Order;
use Shop\InfrastructureBundle\Model\Payment;

class PaymentTokenFactory
{
    /**
     * @var ContainerAwareRegistry
     */
    private $registry;
    /**
     * @var GenericTokenFactoryInterface
     */
    private $tokenFactory;
    /**
     * @var string
     */
    private $currency;

    /**
     * @inheritDoc
     */
    public function __construct(
        ContainerAwareRegistry $registry,
        GenericTokenFactoryInterface $tokenFactory,
        string $currency
    )
    {
        $this->registry = $registry;
        $this->tokenFactory = $tokenFactory;
        $this->currency = $currency;
    }

    /**
     * @param Order $order
     * @param string $gatewayName
     * @return TokenInterface
     */
    public function createToken(Order $order, \string $gatewayName)
    {
        switch ($gatewayName) {
            case 'offline':
                return $this->createOfflineToken($order);
                break;
            case 'paypal':
                return $this->createPaypalToken($order);
                break;
            default:
                throw new \InvalidArgumentException(
                    sprintf('Gateway %s is not found', $gatewayName)
                );
        }
    }

    /**
     * @param Order $order
     * @return TokenInterface
     */
    protected function createOfflineToken(Order $order)
    {
        $payment = $this->createPayment($order);
        $this->registry->getStorage(Payment::class)->update($payment);

        return $this->tokenFactory->createCaptureToken(
            'offline',
            $payment,
            'payment_done'
        );
    }

    /**
     * @param Order $order
     * @return TokenInterface
     */
    protected function createPaypalToken(Order $order)
    {
        $payment = $this->createPayment($order);
        $payment['INVNUM'] = $payment->getNumber();
        $payment['PAYMENTREQUEST_0_CURRENCYCODE'] = $payment->getCurrencyCode();
        $payment['PAYMENTREQUEST_0_AMT'] = $payment->getTotalAmount();
        $this->registry->getStorage(Payment::class)->update($payment);

        return $this->tokenFactory->createCaptureToken(
            'paypal',
            $payment,
            'payment_done'
        );
    }

    /**
     * @param Order $order
     * @return Payment
     */
    protected function createPayment(Order $order)
    {
        /** @var $payment Payment */
        $payment = $this->registry->getStorage(Payment::class)->create();
        $payment->setNumber($order->getId()->getValue());
        $payment->setCurrencyCode($this->currency);
        $payment->setTotalAmount($order->getTotal()->getAmount());
        $payment->setClientId($order->getCustomer()->getId()->getValue());
        $payment->setClientEmail($order->getCustomer()->getEmail());
        $payment->setDescription('Payment for order #' . $order->getId()->getValue());

        return $payment;
    }
}