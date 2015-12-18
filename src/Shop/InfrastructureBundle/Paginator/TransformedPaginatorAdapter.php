<?php

namespace Shop\InfrastructureBundle\Paginator;

use Elastica\Query;
use Elastica\SearchableInterface;
use FOS\ElasticaBundle\Paginator\RawPaginatorAdapter;
use FOS\ElasticaBundle\Paginator\TransformedPartialResults;
use Shop\InfrastructureBundle\Transformer\ProductElasticaToModelTransformer;

class TransformedPaginatorAdapter extends RawPaginatorAdapter
{
    /**
     * @var array for the aggregations
     */
    private $aggregations;

    /**
     * @var ProductElasticaToModelTransformer
     */
    private $transformer;

    /**
     * @param SearchableInterface $searchable the object to search in
     * @param Query $query the query to search
     * @param array $options
     * @param ProductElasticaToModelTransformer $transformer
     */
    public function __construct(
        SearchableInterface $searchable,
        Query $query,
        array $options = array(),
        ProductElasticaToModelTransformer $transformer
    ) {
        parent::__construct($searchable, $query, $options);

        $this->transformer = $transformer;
    }

    /**
     * @inheritDoc
     */
    public function getResults($offset, $length)
    {
        return new TransformedPartialResults($this->getElasticaResults($offset, $length), $this->transformer);
    }

    /**
     * @inheritDoc
     */
    protected function getElasticaResults($offset, $itemCountPerPage)
    {
        $resultSet = parent::getElasticaResults($offset, $itemCountPerPage);
        $this->aggregations = $this->transformer->transformAggregations($resultSet->getAggregation('categories'));

        return $resultSet;
    }

    /**
     * @inheritdoc
     */
    public function getAggregations()
    {
        if (!isset($this->aggregations)) {
            $aggregations = parent::getAggregations();
            $this->aggregations = $this->transformer->transformAggregations($aggregations['categories']);
        }

        return $this->aggregations;
    }
}