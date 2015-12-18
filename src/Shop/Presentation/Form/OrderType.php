<?php

namespace Shop\Presentation\Form;

use Shop\Application\Command\CreateOrderCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', 'Shop\Presentation\Form\AddressType', ['label' => 'form.label.address'])
            ->add('gatewayName', 'Shop\InfrastructureBundle\Form\Type\GatewayType', ['label' => 'form.label.gateway_name'])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', ['label' => 'action.pay']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CreateOrderCommand::class,
            'empty_data' => function(FormInterface $form) {
                return new CreateOrderCommand(
                    $form->get('address')->getData(),
                    $form->get('gatewayName')->getData()
                );
            },
        ));
    }
}