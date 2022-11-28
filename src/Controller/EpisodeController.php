<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/episode', name: 'episode_')]
class EpisodeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('episode/index.html.twig');
    }

    #[Route('/{id<\d+>}', methods: ['GET'], name: 'show')]
    public function show(Episode $episode, EpisodeRepository $episodeRepository): Response
    {
        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode found.'
            );
        }

        return $this->render('episode/show.html.twig', [
            'episode' => $episode
         ]);
    }
}
