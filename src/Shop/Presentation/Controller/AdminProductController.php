<?php

namespace Shop\Presentation\Controller;

use Shop\Application\Command\CreateProductCommand;
use Shop\Application\Command\UpdateProductCommand;
use Shop\Application\Model\ProductOption;
use Shop\Application\Query\GetFilteredProductsQuery;
use Shop\Application\Query\GetProductByIdQuery;
use Shop\Domain\Product\ProductOptionView;
use Shop\Domain\Product\ProductView;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class AdminProductController
{
    /**
     * @var MessageBus
     */
    private $queryBus;
    /**
     * @var EngineInterface
     */
    private $engine;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var MessageBus
     */
    private $commandBus;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @inheritDoc
     */
    public function __construct(
        MessageBus $commandBus,
        MessageBus $queryBus,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        EngineInterface $engine
    ) {
        $this->queryBus = $queryBus;
        $this->engine = $engine;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function indexAction(Request $request)
    {
        $form = $this->getProductFilterForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            /** @var GetFilteredProductsQuery $productQuery */
            $query = $form->getData();
        } else {
            $query = new GetFilteredProductsQuery();
        }
        $this->queryBus->handle($query);

        return new Response(
            $this->engine->render(
                ':admin/product:index.html.twig',
                [
                    'pager' => $query->getResult(),
                    'form' => $form->createView(),
                ]
            )
        );
    }

    public function createAction(Request $request)
    {
        $command = new CreateProductCommand();
        $command->setProductOptions([new ProductOption()]);
        $form = $this->formFactory->create('Shop\Presentation\Form\ProductType', $command);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new RedirectResponse($this->router->generate('admin_product', ['id' => $command->getId()]));
        }

        return new Response(
            $this->engine->render(':admin/product:form.html.twig', ['form' => $form->createView()])
        );
    }

    public function viewAction($id, Request $request)
    {
        $query = new GetProductByIdQuery($id);
        $this->queryBus->handle($query);
        /** @var ProductView $product */
        $product = $query->getResult();
        $command = new UpdateProductCommand(
            $product->getId(),
            $product->getName(),
            $product->getPrice(),
            $product->getCategory()->getId(),
            $product->getDescription(),
            $product->isAvailable(),
            $product->getImageUrl()
        );
        $command->setProductOptions(
            array_map(
                function (ProductOptionView $optionView) {
                    $productOption = new ProductOption();
                    $productOption->setOption($optionView->getOption()->getId());
                    $productOption->setValue($optionView->getValue());

                    return $productOption;
                },
                $product->getProductOptions()
            )
        );
        $form = $this->formFactory->create('Shop\Presentation\Form\ProductType', $command);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new RedirectResponse($this->router->generate('admin_product', ['id' => $command->getId()]));
        }

        return new Response(
            $this->engine->render(':admin/product:form.html.twig', ['form' => $form->createView()])
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getProductFilterForm()
    {
        return $this->formFactory->createNamed(
            '',
            'Shop\Presentation\Form\AdminProductFilterType',
            null,
            [
                'action' => $this->router->generate('admin_products'),
                'method' => 'GET',
            ]
        );
    }
}