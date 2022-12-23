<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'category__get', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category_controller//index.html.twig', [
            'categorys' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'category__create', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('category__get', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category_controller//new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'category__get_by_id', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category_controller//show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'category__update', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('category__get', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category_controller//edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'category__delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('category__get', [], Response::HTTP_SEE_OTHER);
    }
}
