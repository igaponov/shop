<?php

namespace Shop\InfrastructureBundle\Repository\Predis;

use Predis\Client;
use Shop\Domain\Customer\CustomerView;
use Shop\Domain\Customer\CustomerViewRepository as BaseCustomerViewRepository;

class CustomerViewRepository implements BaseCustomerViewRepository
{
    const KEY = 'customer';
    /**
     * @var Client
     */
    private $client;
    /**
     * @var BaseCustomerViewRepository
     */
    private $repository;

    public function __construct(Client $client, BaseCustomerViewRepository $repository)
    {
        $this->client = $client;
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function getAll(\int $page = 1, array $orderBy = [], \int $limit = self::LIMIT) : array
    {
        if ($this->client->exists(self::KEY)) {
            $options = [];
            if ($orderBy) {
                $options[] = 'BY';
            }
            foreach ($orderBy as $sort => $order) {
                array_push($options, $sort, $order);
            }
            array_push($options, 'LIMIT', $page - 1, $limit, 'GET', '#');
            $data = $this->client->sort(self::KEY, $options);
            $models = [];
            foreach ($data as $item) {
                $models[] = $this->createViewModel($item);
            }
        } else {
            $models = $this->repository->getAll($page, $orderBy, $limit);
        }

        return $models;
    }

    /**
     * @inheritdoc
     */
    public function getById(\string $id) : CustomerView
    {
        $key = self::KEY.':'.$id;
        if ($this->client->exists($key)) {
            $item = $this->client->hgetall($key);
            $model = $this->createViewModel($item);
        } else {
            $model = $this->repository->getById($id);
            $this->save($model);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function save(CustomerView $view)
    {
        $this->client->hmset(
            self::KEY.':'.$view->getId(),
            [
                'id' => $view->getId(),
                'email' => $view->getEmail(),
                'country' => $view->getCountry(),
                'city' => $view->getCity(),
                'street' => $view->getStreet(),
                'zipCode' => $view->getZipCode(),
            ]
        );
    }


    protected function createViewModel($item)
    {
        return CustomerView::create(
            $item['id'],
            $item['email'],
            $item['country'],
            $item['city'],
            $item['street'],
            $item['zipCode']
        );
    }
}