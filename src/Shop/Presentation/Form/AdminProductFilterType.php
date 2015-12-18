<?php

namespace Shop\Presentation\Form;

use Shop\Domain\DirectoryViewRepository;
use Symfony\Component\Form\FormBuilderInterface;

class AdminProductFilterType extends ProductFilterType
{
    /**
     * @var string
     */
    private $currency;

    /**
     * @inheritDoc
     */
    public function __construct(DirectoryViewRepository $repository, $currency)
    {
        parent::__construct($repository);
        $this->currency = $currency;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('priceFrom', 'Symfony\Component\Form\Extension\Core\Type\MoneyType', [
                'label' => 'form.label.price_from',
                'currency' => $this->currency,
                'divisor' => 100,
                'required' => false,
            ])
            ->add('priceTo', 'Symfony\Component\Form\Extension\Core\Type\MoneyType', [
                'label' => 'form.label.price_to',
                'currency' => $this->currency,
                'divisor' => 100,
                'required' => false,
            ])
            ->add('availability', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => 'form.label.available',
                'choices' => [
                    true => 'message.in_stock',
                    false => 'message.out_of_stock',
                ],
                'placeholder' => 'form.placeholder.all',
                'required' => false,
            ]);
    }
}