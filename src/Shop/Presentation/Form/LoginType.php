<?php

namespace Shop\Presentation\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', 'Symfony\Component\Form\Extension\Core\Type\TextType', ['label' => 'form.label.username'])
            ->add('_password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', ['label' => 'form.label.password'])
            ->add('_target_path', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', ['label' => 'action.login']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'POST',
        ));
    }
}