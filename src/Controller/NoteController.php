<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/note')]
class NoteController extends AbstractController
{
    #[Route('/', name: 'app_note_index', methods: ['GET'])]
    public function index(NoteRepository $noteRepository): Response
    {
        return $this->render('note/index.html.twig', [
            'notes' => $noteRepository->findAll(),
        ]);
    }

    #[Route('/adminnote', name: 'app_note_adminnote', methods: ['GET'])]
    public function admin(NoteRepository $noteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer toutes les notes
        $allNotes = $noteRepository->findAll();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $allNotes, // Requête Doctrine (pas le tableau, mais la requête SQL)
            $request->query->getInt('page', 1), // Numéro de page
            10 // Limite par page
        );

        return $this->render('note/adminnote.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_note_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();
            $this->addFlash('success', 'Merci pour votre note !');
            return $this->redirectToRoute('app_note_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('note/new.html.twig', [
            'note' => $note,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_note_show', methods: ['GET'])]
    public function show(Note $note): Response
    {
        return $this->render('note/show.html.twig', [
            'note' => $note,
        ]);
    }

    #[Route('showadmin/{id}', name: 'app_note_showadmin', methods: ['GET'])]
    public function showAdmin(Note $note): Response
    {
        return $this->render('note/adminshow.html.twig', [
            'note' => $note,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_note_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Note $note, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('note/edit.html.twig', [
            'note' => $note,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_note_delete', methods: ['POST'])]
    public function delete(Request $request, Note $note, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $note->getId(), $request->request->get('_token'))) {
            $entityManager->remove($note);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_note_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/notes/stats", name="note_stats")
     */
    public function showStats(EntityManagerInterface $entityManager): Response
    {
        $connection = $entityManager->getConnection();

        $sql = '
            SELECT
                COUNT(CASE WHEN description = "1" THEN 1 END) as countAngry,
                COUNT(CASE WHEN description = "2" THEN 1 END) as countMad,
                COUNT(CASE WHEN description = "3" THEN 1 END) as countNeutral,
                COUNT(CASE WHEN description = "4" THEN 1 END) as countHappy,
                COUNT(CASE WHEN description = "5" THEN 1 END) as countVeryHappy
            FROM note
        ';

        $stats = $connection->executeQuery($sql)->fetchAssociative();

        return $this->render('note/stats.html.twig', [
            'stats' => $stats,
        ]);
    }
}
