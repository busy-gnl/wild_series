<?php

namespace App\Controller;

use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;

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
