<?php

namespace Shop\Application\Service;

use Ramsey\Uuid\Uuid;
use Shop\Application\Command\RegisterUserCommand;
use Shop\Application\Command\UpdateProfileCommand;
use Shop\Application\Event\ProfileUpdatedEvent;
use Shop\Application\Event\UserRegisteredEvent;
use Shop\Application\Query\GetActualCustomerQuery;
use Shop\Domain\Customer\CustomerRepository;
use Shop\Domain\Customer\CustomerViewRepository;
use Shop\Domain\ValueObject\Address;
use Shop\Domain\ValueObject\UuidIdentity;
use Shop\InfrastructureBundle\Security\User;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserService
{
    /**
     * @var CustomerRepository
     */
    private $repository;
    /**
     * @var CustomerViewRepository
     */
    private $viewRepository;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * @inheritDoc
     */
    public function __construct(
        CustomerRepository $repository,
        CustomerViewRepository $viewRepository,
        TokenStorageInterface $tokenStorage,
        EncoderFactoryInterface $encoderFactory,
        MessageBus $eventBus
    )
    {
        $this->repository = $repository;
        $this->viewRepository = $viewRepository;
        $this->tokenStorage = $tokenStorage;
        $this->encoderFactory = $encoderFactory;
        $this->eventBus = $eventBus;
    }

    public function registerUser(RegisterUserCommand $command)
    {
        $salt = random_bytes(22);
        $password = $this->encoderFactory
            ->getEncoder(User::class)
            ->encodePassword($command->getPassword(), $salt);
        $user = new User(
            new UuidIdentity(Uuid::uuid4()),
            $command->getUsername(),
            $password,
            $salt,
            User::ROLE_USER,
            new Address()
        );
        $this->repository->save($user);

        $event = new UserRegisteredEvent($user);
        $this->eventBus->handle($event);
    }

    public function updateProfile(UpdateProfileCommand $command)
    {
        $user = $this->getActualUser();
        $address = $command->getAddress();
        $user->changeAddress(new Address(
            $address->getCountry(),
            $address->getCity(),
            $address->getStreet(),
            $address->getZipCode()
        ));
        $this->repository->save($user);

        $event = new ProfileUpdatedEvent($user);
        $this->eventBus->handle($event);
    }

    public function getActualCustomer(GetActualCustomerQuery $query)
    {
        $user = $this->getActualUser();
        $result = $this->viewRepository->getById($user->getId());
        $query->setResult($result);
    }

    /**
     * @return User
     */
    private function getActualUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException;
        }

        return $user;
    }
}