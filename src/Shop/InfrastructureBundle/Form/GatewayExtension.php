<?php

namespace Shop\InfrastructureBundle\Form;

use Payum\Bundle\PayumBundle\Registry\ContainerAwareRegistry;
use Shop\InfrastructureBundle\Form\Type\GatewayType;
use Symfony\Component\Form\AbstractExtension;

class GatewayExtension extends AbstractExtension
{
    /**
     * @var ContainerAwareRegistry
     */
    private $registry;

    public function __construct(ContainerAwareRegistry $registry)
    {
        $this->registry = $registry;
    }

    protected function loadTypes()
    {
        return array(
            new GatewayType($this->registry),
        );
    }
}
