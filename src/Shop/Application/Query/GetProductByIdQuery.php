<?php

namespace Shop\Application\Query;

class GetProductByIdQuery extends AbstractQuery
{
    /**
     * @var string
     */
    private $id;

    /**
     * @inheritDoc
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}