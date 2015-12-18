<?php

namespace Shop\Application\Subscriber;

use Shop\Application\Event\ProfileUpdatedEvent;
use Shop\Domain\Customer\CustomerView;
use Shop\Domain\Customer\CustomerViewRepository;

class CustomerViewUpdateSubscriber
{
    /**
     * @var CustomerViewRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function __construct(CustomerViewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function notify(ProfileUpdatedEvent $event)
    {
        $user = $event->getUser();
        $view = CustomerView::create(
            $user->getId(),
            $user->getUsername(),
            $user->getAddress()->getCountry(),
            $user->getAddress()->getCity(),
            $user->getAddress()->getStreet(),
            $user->getAddress()->getZipCode()
        );
        $this->repository->save($view);
    }
}