<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Service\ProgramDuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', methods: ['GET'], name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }
    
    #[Route('/new', name: 'new')]
    public function new(
        Request $request, 
        ProgramRepository $programRepository, 
        SluggerInterface $slugger,
        MailerInterface $mailer): Response
    {
        // Create a new Category Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            $program->setSlug($slugger->slug($program->getTitle())->lower());
            $programRepository->save($program, true);

            $this->addFlash('success', 'The new program has been created');
            
            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('mickael.lambert2@gmail.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/email_new.html.twig', ['program' => $program]));

            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }

        // Render the form
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('/{slug}', methods: ['GET'], name: 'show')]
    public function show(Program $program, ProgramRepository $programRepository, ProgramDuration $programDuration): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program found.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'duration' => $programDuration->calculate($program),
            'seasons' => $program->getSeasons()
         ]);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Program $program, 
        ProgramRepository $programRepository,
        MailerInterface $mailer): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program found.'
            );
        }
        
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $programRepository->remove($program, true);

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('mickael.lambert2@gmail.com')
                ->subject('Une série vient d\'être supprimée !')
                ->html($this->renderView('program/email_delete.html.twig', ['program' => $program]));

            $mailer->send($email);
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}