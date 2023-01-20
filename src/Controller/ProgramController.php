<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\ProgramType;
use App\Form\SearchProgramType;
use App\Service\ProgramDuration;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/program')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'program_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(SearchProgramType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs = $programRepository->findLikeName($search);
        } else {
            $programs = $programRepository->findAll();
        }

        return $this->renderForm('program/index.html.twig', [
            'programs' => $programs,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'program_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        MailerInterface $mailer,
        ProgramRepository $programRepository,
        SluggerInterface $slugger
    ): Response {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setPoster($slug);
            $program->setOwner($this->getUser());
            $programRepository->save($program, true);

            $email = (new Email())

                ->from($this->getParameter('mailer_from'))

                ->to('vallantjesse@live.com')

                ->subject('Une nouvelle série vient d\'être publiée !')

                ->html($this->renderView('mails/new_program.html.twig', ['program' => $program]));


            $mailer->send($email);

            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }



        return $this->renderForm('program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'program_show', methods: ['GET'])]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program)
        ]);
    }

    #[Route('/{slug}/edit', name: 'program_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Program $program,
        ProgramRepository $programRepository,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($this->getUser() !== $program->getOwner()) {

            // If not the owner, throws a 403 Access Denied exception

            throw $this->createAccessDeniedException('Only the owner can edit the program!');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $programRepository->save($program, true);

            $this->addFlash('success', 'The program has been edited');

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'program_delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getSlug(), $request->request->get('_token'))) {
            $programRepository->remove($program, true);
        }
        $this->addFlash('danger', 'The program has been deleted');
        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{programSlug}/season/{seasonId}', name: 'program_season_show')]
    #[Entity('program', options: ['mapping' => ['programSlug' => 'slug']])]
    #[Entity('season', options: ['mapping' => ['seasonId' => 'id']])]
    public function showSeason(Program $program, Season $season)
    {

        return $this->render('program/season_show.html.twig', [
            "program" => $program,
            "season" => $season
        ]);
    }

    #[Route('/{programSlug}/season/{seasonId}/episode/{episodeSlug}', name: 'program_episode_show')]
    #[Entity('program', options: ['mapping' => ['programSlug' => 'slug']])]
    #[Entity('season', options: ['mapping' => ['seasonId' => 'id']])]
    #[Entity('episode', options: ['mapping' => ['episodeSlug' => 'slug']])]
    public function showEpisode(
        Program $program,
        Season $season,
        Episode $episode,
        CommentRepository $commentRepository
    ) {

        return $this->render('program/episode_show.html.twig', [
            "program" => $program,
            "season" => $season,
            "episode" => $episode,
            "comments" => $commentRepository->findAll()
        ]);
    }

    #[Route('/{id}/watchlist', name: 'program_watchlist', methods: ["GET", "POST"])]
    public function addToWatchlist(Program $program, UserRepository $userRepository): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with this id found in program\'s table.'
            );
        }

        /** @var \App\Entity\User */
        $user = $this->getUser();
        if ($user->isInWatchlist($program)) {
            $user->removeFromWatchlist($program);
        } else {
            $user->addToWatchlist($program);
        }

        $userRepository->save($user, true);

        return $this->redirectToRoute('program_show', ['slug' => $program->getSlug()], Response::HTTP_SEE_OTHER);
    }
}
