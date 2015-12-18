<?php

namespace Shop\Presentation\Form;

use Shop\Application\Command\AddProductCommand;
use Shop\Domain\Product\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProductType extends AbstractType
{
    /**
     * @var ProductRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => 'form.label.quantity',
                'attr' => [
                    'min' => 0
                ]
            ])
            ->add('productId', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => 'action.add_product',
                'attr' => ['class' => 'btn-success']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AddProductCommand::class,
            'empty_data' => function (FormInterface $form) {
                return new AddProductCommand(
                    $form->get('productId')->getData(),
                    $form->get('quantity')->getData()
                );
            },
            'csrf_protection' => false,
        ));
    }
}