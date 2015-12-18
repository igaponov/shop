<?php

namespace Shop\InfrastructureBundle\Repository\DBAL;

use Doctrine\DBAL\Connection;
use Shop\Domain\Customer\CustomerView;
use Shop\Domain\Customer\CustomerViewRepository as BaseCustomerViewRepository;
use Shop\InfrastructureBundle\Exception\EntityNotFoundException;

class CustomerViewRepository implements BaseCustomerViewRepository
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @inheritDoc
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @inheritdoc
     */
    public function getAll(\int $page = 1, array $orderBy = [], \int $limit = self::LIMIT) : array
    {
        $query = $this->conn->createQueryBuilder()
            ->select('t.id, t.username, t.country, t.city, t.street, t.zip_code as zipCode')
            ->from('users', 't')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        foreach ($orderBy as $sort => $order) {
            $query->addOrderBy($sort, $order);
        }

        $statement = $query->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, CustomerView::class);

        return $statement->fetchAll();
    }

    /**
     * @param string $id
     * @return CustomerView
     */
    public function getById(\string $id) : CustomerView
    {
        $query = $this->conn->createQueryBuilder()
            ->select('t.id, t.username, t.country, t.city, t.street, t.zip_code as zipCode')
            ->from('users', 't')
            ->where('t.id = :id')
            ->setParameter(':id', $id, \PDO::PARAM_STR);

        $statement = $query->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, CustomerView::class);
        $result = $statement->fetch();

        if (!$result) {
            throw new EntityNotFoundException;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function save(CustomerView $view)
    {
        throw new \RuntimeException('Method is not implemented');
    }
}