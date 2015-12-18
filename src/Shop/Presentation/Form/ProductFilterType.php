<?php

namespace Shop\Presentation\Form;

use Shop\Application\Query\GetFilteredProductsQuery;
use Shop\Domain\DirectoryViewRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterType extends AbstractType
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
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'form.label.query',
                'required' => false,
            ])
            ->add(
                'category',
                'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'label' => 'form.label.category',
                    'choices' => $this->repository->getAllIndexed(),
                    'placeholder' => 'form.placeholder.all',
                    'required' => false,
                    'choice_translation_domain' => false,
                ]
            )
            ->add(
                'page',
                'Symfony\Component\Form\Extension\Core\Type\IntegerType',
                [
                    'empty_data' => '1',
                ]
            )
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', ['label' => 'action.search']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => GetFilteredProductsQuery::class,
                'empty_data' => function (FormInterface $form) {
                    return new GetFilteredProductsQuery(
                        $form->get('query')->getData(),
                        $form->get('category')->getData(),
                        max(1, $form->get('page')->getData())
                    );
                },
                'csrf_protection' => false,
            )
        );
    }
}