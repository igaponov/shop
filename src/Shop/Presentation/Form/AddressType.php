<?php

namespace Shop\Presentation\Form;

use Shop\Application\Model\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', 'Symfony\Component\Form\Extension\Core\Type\CountryType', ['label' => 'form.label.country'])
            ->add('city', 'Symfony\Component\Form\Extension\Core\Type\TextType', ['label' => 'form.label.city'])
            ->add('street', 'Symfony\Component\Form\Extension\Core\Type\TextType', ['label' => 'form.label.street'])
            ->add('zipCode', 'Symfony\Component\Form\Extension\Core\Type\TextType', ['label' => 'form.label.zip_code']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Address::class,
            'empty_data' => function(FormInterface $form) {
                return new Address(
                    $form->get('country')->getData(),
                    $form->get('city')->getData(),
                    $form->get('street')->getData(),
                    $form->get('zipCode')->getData()
                );
            },
        ));
    }
}