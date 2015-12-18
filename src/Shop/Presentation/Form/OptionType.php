<?php

namespace Shop\Presentation\Form;

use Shop\Application\Model\ProductOption;
use Shop\Domain\DirectoryViewRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionType extends AbstractType
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
            ->add('option', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'choices' => $this->repository->getAllIndexed(),
            ])
            ->add('value', 'Symfony\Component\Form\Extension\Core\Type\TextType');
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductOption::class,
        ]);
    }
}