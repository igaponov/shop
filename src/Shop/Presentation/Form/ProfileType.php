<?php

namespace Shop\Presentation\Form;

use Shop\Application\Command\UpdateProfileCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', 'Shop\Presentation\Form\AddressType', [
                'label' => 'form.label.address',
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', ['label' => 'action.update']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UpdateProfileCommand::class,
            'empty_data' => function(FormInterface $form) {
                return new UpdateProfileCommand(
                    $form->get('country')->getData(),
                    $form->get('city')->getData(),
                    $form->get('street')->getData(),
                    $form->get('zipCode')->getData()
                );
            },
        ));
    }
}