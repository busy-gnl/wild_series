<?php

namespace App\Controller;

use App\Entity\Season;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/program', name: 'program_')]

class ProgramController extends AbstractController

{

    #[Route('/', name: 'index')]

    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render(

            'program/index.html.twig',

            ['programs' => $programs]

        );
    }


    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]

    public function show(ProgramRepository $programRepository, SeasonRepository $seasonRepository, int $id = 1): Response

    {

        $program = $programRepository->findOneBy(['id' => $id]);
        // same as $program = $programRepository->find($id);


        if (!$program) {

            throw $this->createNotFoundException(

                'No program with id : ' . $id . ' found in program\'s table.'

            );
        }

        return $this->render('program/show.html.twig', [

            'program' => $program,


        ]);
    }

    #[Route('/{programId}/season/{seasonId}', name: 'season_show')]
    public function showSeason(ProgramRepository $programRepository, SeasonRepository $seasonRepository, int $programId, int $seasonId)
    {

        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findOneBy(['id' => $seasonId]);

        if (!$program) {

            throw $this->createNotFoundException(

                'No program with id : ' . $programId . ' found in program\'s table.'

            );
        }
        if (!$season) {

            throw $this->createNotFoundException(

                'No season with id : ' . $seasonId . ' found in season\'s table.'

            );
        }
        return $this->render('program/season_show.html.twig', [
            "program" => $program,
            "season" => $season
        ]);
    }
}
