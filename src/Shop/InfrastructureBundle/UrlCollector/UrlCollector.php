<?php

namespace Shop\InfrastructureBundle\UrlCollector;

class UrlCollector implements UrlCollectorInterface
{
    /**
     * @var UrlCollectorInterface[]
     */
    private $collectors;

    /**
     * @inheritDoc
     */
    public function __construct(array $collectors = [])
    {
        foreach ($collectors as $collector) {
            if (!$collector instanceof UrlCollectorInterface) {
                throw new \InvalidArgumentException('Collector must be of type '.UrlCollectorInterface::class);
            }
            $this->collectors[] = $collector;
        }
    }

    /**
     * @inheritDoc
     */
    public function collect(): \Iterator
    {
        $iterator = new \AppendIterator();
        foreach ($this->collectors as $collector) {
            $iterator->append($collector->collect());
        }

        return $iterator;
    }
}