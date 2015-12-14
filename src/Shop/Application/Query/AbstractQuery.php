<?php

namespace Shop\Application\Query;

abstract class AbstractQuery
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}