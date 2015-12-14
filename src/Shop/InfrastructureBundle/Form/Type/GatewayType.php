<?php

namespace Shop\InfrastructureBundle\Form\Type;

use Payum\Bundle\PayumBundle\Registry\ContainerAwareRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GatewayType extends AbstractType
{
    /**
     * @var ContainerAwareRegistry
     */
    private $registry;

    /**
     * @inheritDoc
     */
    public function __construct(ContainerAwareRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $keys = array_keys($this->registry->getGateways());
        $resolver->setDefaults(
            [
                'choices_as_values' => true,
                'choices' => array_combine($keys, $keys),
                'choice_label' => function ($currentChoiceKey) {
                    return 'form.choice.'.$currentChoiceKey;
                },
            ]
        );
    }

    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\ChoiceType';
    }
}