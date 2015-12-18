<?php

namespace Shop\InfrastructureBundle\Transformer;

use Elastica\Result;
use FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;
use Shop\Domain\Category\CategoryView;
use Shop\Domain\DirectoryView;
use Shop\Domain\DirectoryViewRepository;
use Shop\Domain\Option\OptionView;
use Shop\Domain\Product\ProductOptionView;
use Shop\Domain\Product\ProductView;

class ProductElasticaToModelTransformer implements ElasticaToModelTransformerInterface
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

    /**
     * @inheritdoc
     **/
    public function transform(array $elasticaObjects)
    {
        return array_map(
            function (Result $result) {
                return $this->transformObject($result->getData());
            },
            $elasticaObjects
        );
    }

    /**
     * Transforms array to object
     *
     * @param array $data
     * @return static
     */
    public function transformObject(array $data)
    {
        $category = CategoryView::create($data['category']['id'], $data['category']['name']);
        $productOptions = array_map(
            function ($item) {
                $option = OptionView::create($item['option']['id'], $item['option']['name']);
                return ProductOptionView::create($option, $item['value']);
            },
            $data['productOptions']
        );

        return ProductView::create(
            $data['id'],
            $data['name'],
            $data['price'],
            $category,
            $data['description'],
            $data['availability'],
            $data['imageUrl'],
            $data['updated'],
            $productOptions
        );
    }

    /**
     * @inheritdoc
     */
    public function hybridTransform(array $elasticaObjects)
    {
        throw new \RuntimeException('The method is not implemented');
    }

    /**
     * Transforms aggregates to objects
     *
     * @param array $aggregations
     * @return array
     */
    public function transformAggregations(array $aggregations = [])
    {
        if (!empty($aggregations['buckets'])) {
            $aggregations = array_column($aggregations['buckets'], 'doc_count', 'key');
        }

        $data = [];
        $allIndexed = $this->repository->getAllIndexed();
        foreach ($allIndexed as $id => $name) {
            $data[$id] = DirectoryView::create($id, $name, $aggregations[$id] ?? 0);
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function getObjectClass()
    {
        return ProductView::class;
    }

    /**
     * @inheritdoc
     */
    public function getIdentifierField()
    {
        return 'id';
    }
}