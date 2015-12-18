<?php

namespace Shop\Application\Service;

use Shop\Application\Query\GetCategoryByIdQuery;
use Shop\Domain\DirectoryViewRepository;

class CategoryService
{
    /**
     * @var DirectoryViewRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function __construct(DirectoryViewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCategoryById(GetCategoryByIdQuery $query)
    {
        $result = $this->repository->getById($query->getId());
        $query->setResult($result);
    }
}