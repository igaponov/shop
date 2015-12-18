<?php

namespace Shop\Presentation\Form;

use Shop\Application\Command\RegisterUserCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'Symfony\Component\Form\Extension\Core\Type\EmailType', ['label' => 'form.label.username'])
            ->add('password', 'Symfony\Component\Form\Extension\Core\Type\RepeatedType', array(
                    'type' => 'password',
                    'first_options'  => array('label' => 'form.label.password'),
                    'second_options' => array('label' => 'form.label.password_repeat'),
                )
            )
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', ['label' => 'action.register']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => RegisterUserCommand::class,
            'empty_data' => function(FormInterface $form) {
                return new RegisterUserCommand(
                    $form->get('username')->getData(),
                    $form->get('password')->getData()
                );
            },
        ));
    }
}