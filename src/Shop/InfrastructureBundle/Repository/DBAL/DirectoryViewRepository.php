<?php

namespace Shop\InfrastructureBundle\Repository\DBAL;

use Doctrine\DBAL\Connection;
use Shop\Domain\DirectoryView;
use Shop\Domain\DirectoryViewRepository as BaseDirectoryViewRepository;
use Shop\InfrastructureBundle\Exception\EntityNotFoundException;

class DirectoryViewRepository implements BaseDirectoryViewRepository
{
    /**
     * @var string
     */
    private $tableName;
    /**
     * @var string
     */
    private $viewClass;
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @inheritDoc
     */
    public function __construct(string $tableName, string $viewClass, Connection $conn)
    {
        $this->tableName = $tableName;
        $this->viewClass = $viewClass;
        $this->conn = $conn;
    }

    /**
     * @inheritdoc
     */
    public function getAll() : array
    {
        $statement = $this->conn->createQueryBuilder()
            ->select('*')
            ->from($this->tableName)
            ->orderBy('name', 'ASC')
            ->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->viewClass);

        return $statement->fetchAll();
    }

    /**
     * @inheritdoc
     */
    public function getAllIndexed() : array
    {
        $statement = $this->conn->createQueryBuilder()
            ->select('id, name')
            ->from($this->tableName)
            ->orderBy('name', 'ASC')
            ->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param $id
     * @return DirectoryView
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getById($id) : DirectoryView
    {
        $statement = $this->conn->createQueryBuilder()
            ->select('*')
            ->from($this->tableName)
            ->where('id = :id')
            ->setParameter(':id', $id)
            ->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->viewClass);
        $result = $statement->fetch();

        if (!$result) {
            throw new EntityNotFoundException;
        }

        return $result;
    }
}