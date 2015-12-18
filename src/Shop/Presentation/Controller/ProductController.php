<?php

namespace Shop\Presentation\Controller;

use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Pagerfanta\Pagerfanta;
use Shop\Application\Command\AddProductCommand;
use Shop\Application\Query\GetCategoryByIdQuery;
use Shop\Application\Query\GetFilteredProductsQuery;
use Shop\Application\Query\GetProductAvailabilityByIdQuery;
use Shop\Application\Query\GetProductByIdQuery;
use Shop\Presentation\Form\AddProductType;
use Shop\Presentation\Form\ProductFilterType;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class ProductController
{
    /**
     * @var EngineInterface
     */
    private $engine;
    /**
     * @var MessageBus
     */
    private $queryBus;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @inheritDoc
     */
    public function __construct(
        EngineInterface $engine,
        MessageBus $queryBus,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->engine = $engine;
        $this->queryBus = $queryBus;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function indexAction(Request $request)
    {
        $form = $this->getProductFilterForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            /** @var GetFilteredProductsQuery $productQuery */
            $productQuery = $form->getData();
        } else {
            $productQuery = new GetFilteredProductsQuery();
        }
        $this->queryBus->handle($productQuery);
        /** @var Pagerfanta $pager */
        $pager = $productQuery->getResult();
        $products = $pager->getCurrentPageResults();
        $category = $this->getCategoryById($productQuery->getCategory());
        /** @var FantaPaginatorAdapter $adapter */
        $adapter = $pager->getAdapter();
        $categories = $adapter->getAggregations();
        $response = new Response(
            $this->engine->render(
                ':product:index.html.twig',
                [
                    'query' => $productQuery,
                    'category' => $category,
                    'pager' => $pager,
                    'products' => $products,
                    'categories' => $categories,
                ]
            )
        );
        if (!array_filter($request->query->all())) {
            $response->setSharedMaxAge(3600);
        }

        return $response;
    }

    public function viewAction($id)
    {
        $query = new GetProductByIdQuery($id);
        $this->queryBus->handle($query);
        $response = new Response(
            $this->engine->render(
                ':product:view.html.twig',
                [
                    'product' => $query->getResult(),
                ]
            )
        );
        $response->setSharedMaxAge(3600);

        return $response;
    }

    public function addProductFormAction($id)
    {
        $query = new GetProductAvailabilityByIdQuery($id);
        $this->queryBus->handle($query);
        $form = $this->getAddProductForm($id);

        return new Response(
            $this->engine->render(
                ':product:add_product_form.html.twig',
                [
                    'available' => $query->getResult(),
                    'form' => $form->createView(),
                ]
            )
        );
    }

    public function filterAction()
    {
        $form = $this->getProductFilterForm();
        $response = new Response(
            $this->engine->render(':product:filter.html.twig', ['form' => $form->createView()])
        );
        $response->setSharedMaxAge(3600);

        return $response;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getProductFilterForm()
    {
        return $this->formFactory->createNamed(
            '',
            'Shop\Presentation\Form\ProductFilterType',
            null,
            [
                'action' => $this->router->generate('products'),
                'method' => 'GET',
            ]
        );
    }

    /**
     * @param string $productId
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getAddProductForm(\string $productId)
    {
        $form = $this->formFactory->createNamed(
            '',
            'Shop\Presentation\Form\AddProductType',
            new AddProductCommand($productId, 1),
            [
                'action' => $this->router->generate('add_product'),
            ]
        );

        return $form;
    }

    private function getCategoryById($id)
    {
        if ($id) {
            $query = new GetCategoryByIdQuery($id);
            if ($query->getId()) {
                $this->queryBus->handle($query);
            }

            return $query->getResult();
        }

        return null;
    }
}