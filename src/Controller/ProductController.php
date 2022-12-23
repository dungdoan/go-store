<?php

namespace App\Controller;

use App\Entity\Product;
use App\EventListener\ProductListener;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product__get', methods: ['GET'])]
    public function index(ProductRepository $productRepository, EventDispatcherInterface $dispatcher): Response
    {
        $event = new ProductListener();
        $dispatcher->dispatch($event, ProductListener::PRODUCT_ADDED_EVENT);

        return $this->render('product//index.html.twig', [
//            'products' => $productRepository->findAll(),
            'products' => [],
        ]);
    }

    #[Route('/new', name: 'app_product__create', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product__get', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_controller//new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product__get_by_id', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product_controller//show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product__update', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product__get', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_controller//edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product__delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product__get', [], Response::HTTP_SEE_OTHER);
    }
}
