<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/category', name: 'category_')]

class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render(

            'category/index.html.twig',

            ['categories' => $categories, 'controller_name' => 'CategoryController']

        );
    }

    /**
     * The controller for the category add form
     * Display the form or deal with it
     */
    #[Route('/new', name: 'new')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $categoryRepository->save($category, true);

            // Redirect to categories list
            return $this->redirectToRoute('category_index');
        }

        // Render the form
        return $this->renderForm('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{categoryName}', name: 'show')]

    public function show(CategoryRepository $categoryRepository, ProgramRepository $programRepository, string $categoryName): Response

    {

        $category = $categoryRepository->findOneBy(['name' => $categoryName]);
        // same as $category = $categoryRepository->find($id);


        if (!$category) {

            throw $this->createNotFoundException(

                'No category with name : ' . $categoryName . ' found in category\'s table.'

            );
        }
        $programs = $programRepository->findBy(['category' => $category], ['id' => 'DESC'], 3);

        return $this->render('category/show.html.twig', [

            'category' => $category,
            'programs' => $programs

        ]);
    }
}
