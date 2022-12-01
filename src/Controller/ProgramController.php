<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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


    #[Route('/show/{program}', name: 'show')]
    public function show(Program $program): Response

    {
        return $this->render('program/show.html.twig', [

            'program' => $program,


        ]);
    }

    #[Route('/{program}/season/{season}', name: 'season_show')]
    public function showSeason(Program $program, Season $season)
    {

        return $this->render('program/season_show.html.twig', [
            "program" => $program,
            "season" => $season
        ]);
    }



    #[Route('/program/{programId}/season/{seasonId}/episode/{episodeId}', name: 'episode_show')]
    #[Entity('program', options: ['mapping' => ['programId' => 'id']])]
    #[Entity('season', options: ['mapping' => ['seasonId' => 'id']])]
    #[Entity('episode', options: ['mapping' => ['episodeId' => 'id']])]
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            "program" => $program,
            "season" => $season,
            "episode" => $episode
        ]);
    }
}
