<?php

namespace App\Controller;

use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/season', name: 'season_')]
class SeasonController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('season/index.html.twig');
    }

    #[Route('/{id<\d+>}', methods: ['GET'], name: 'show')]
    public function show(int $id, SeasonRepository $seasonRepository): Response
    {
        $season = $seasonRepository->find($id);

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $id . ' found.'
            );
        }

        return $this->render('season/show.html.twig', [
            'season'   => $season,
            'episodes' => $season->getEpisodes()
         ]);
    }
}
