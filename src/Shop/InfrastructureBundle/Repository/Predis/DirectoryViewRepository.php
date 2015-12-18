<?php

namespace Shop\InfrastructureBundle\Repository\Predis;

use Predis\Client;
use Shop\Domain\DirectoryView;
use Shop\Domain\DirectoryViewRepository as BaseDirectoryViewRepository;

class DirectoryViewRepository implements BaseDirectoryViewRepository
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $viewClass;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var BaseDirectoryViewRepository
     */
    private $repository;

    /**
     * DirectoryViewRepository constructor.
     * @param string $key
     * @param string $viewClass
     * @param Client $client
     * @param BaseDirectoryViewRepository $repository
     */
    public function __construct(\string $key, \string $viewClass, Client $client, BaseDirectoryViewRepository $repository)
    {
        $this->key = $key;
        $this->viewClass = $viewClass;
        $this->client = $client;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getAll() : array
    {
        if (!$this->client->exists($this->key)) {
            $models = $this->repository->getAll();
            if ($models) {
                $this->client->hmset($this->key, $this->repository->getAllIndexed());
            }
        } else {
            $models = [];
            $data = $this->client->executeRaw(['HGETALL', $this->key]);
            for ($i = 0; $i < count($data); ++$i) {
                /** @var DirectoryView $viewClass */
                $viewClass = $this->viewClass;
                $models[] = $viewClass::create($data[$i], $data[++$i]);
            }
        }
        return $models;
    }

    /**
     * @inheritDoc
     */
    public function getAllIndexed() : array
    {
        if (!$this->client->exists($this->key)) {
            $data = $this->repository->getAllIndexed();
            if ($data) {
                $this->client->hmset($this->key, $data);
            }
        } else {
            $data = $this->client->hgetall($this->key);
        }
        return $data;
    }

    /**
     * @param $id
     * @return DirectoryView
     */
    public function getById($id) : DirectoryView
    {
        if (!$name = $this->client->hget($this->key, $id)) {
            $model = $this->repository->getById($id);
            $this->client->hset($this->key, $model->getId(), $model->getName());
            return $model;
        }
        /** @var DirectoryView $viewClass */
        $viewClass = $this->viewClass;
        return $viewClass::create($id, $name);
    }
}