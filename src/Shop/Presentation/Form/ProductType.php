<?php

namespace Shop\Presentation\Form;

use Shop\Application\Command\CreateProductCommand;
use Shop\Domain\DirectoryViewRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * @var DirectoryViewRepository
     */
    private $repository;
    /**
     * @var string
     */
    private $currency;

    /**
     * @inheritDoc
     */
    public function __construct(
        DirectoryViewRepository $repository,
        \string $currency
    )
    {
        $this->repository = $repository;
        $this->currency = $currency;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'form.label.name',
            ])
            ->add('price', 'Symfony\Component\Form\Extension\Core\Type\MoneyType', [
                'label' => 'form.label.price',
                'currency' => $this->currency,
                'divisor' => 100,
            ])
            ->add('category', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => 'form.label.category',
                'choices' => $this->repository->getAllIndexed(),
            ])
            ->add('description', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
                'label' => 'form.label.description',
                'required' => false,
                'attr' => ['rows' => 5],
            ])
            ->add('available', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
                'label' => 'form.label.available',
                'required' => false,
            ])
            ->add('imageUrl', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'form.label.image',
            ])
            ->add(
                'productOptions',
                'Symfony\Component\Form\Extension\Core\Type\CollectionType',
                [
                    'label' => 'form.label.options',
                    'required' => false,
                    'entry_type' => 'Shop\Presentation\Form\OptionType',
                    'entry_options'  => [
                        'required'  => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                ]
            )
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => 'action.submit',
            ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateProductCommand::class,
            ]
        );
    }

}