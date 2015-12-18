<?php

namespace Shop\InfrastructureBundle\Finder;

use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder as BaseTransformedFinder;
use Shop\InfrastructureBundle\Paginator\TransformedPaginatorAdapter;

class TransformedFinder extends BaseTransformedFinder
{
    /**
     * @inheritDoc
     */
    public function createPaginatorAdapter($query, $options = array())
    {
        $query = Query::create($query);

        return new TransformedPaginatorAdapter($this->searchable, $query, $options, $this->transformer);
    }
}