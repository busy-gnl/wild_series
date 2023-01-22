<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('./index.html.twig');
    }

    #[Route('/my-profile', name: 'app_profile')]
    public function userPofile(): Response
    {
        return $this->render('profile/profile.html.twig');
    }


    public function navbarTop(CategoryRepository $categoryRepository): Response
    {
        return $this->render('_navbartop.html.twig', [
            'categories' => $categoryRepository->findBy([], ['name' => 'ASC'])
        ]);
    }
}
