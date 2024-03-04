<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\Contratdatedebut;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Service\PdfService;


#[Route('/contrat')]
class ContratController extends AbstractController
{
    #[Route('/', name: 'app_contrat_index', methods: ['GET'])]
    public function index(Request $request, ContratRepository $contratRepository): Response
    {
        $contrat = new Contrat();
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Contrat::class)
            ->createQueryBuilder('u');
        $contrats = new Paginator($query);
        $currentPage = $request->query->getInt('page', 1);

        $itemsPerPage = 2;
        $contrats
            ->getQuery()
            ->setFirstResult($itemsPerPage * ($currentPage - 1))
            ->setMaxResults($itemsPerPage);
        $totalItems = count($contrats);
        $pagesCount = ceil($totalItems / $itemsPerPage);

        return $this->render('contrat/index.html.twig', [
            'contrats' => $contrats,
            'contrat' => $contrat,
            'currentPage' => $currentPage,
            'pagesCount' => $pagesCount,
        ]);
    }

    //pdf
    #[Route('/pdf/{id}', name: 'contrat_pdf')]
    public function generatePdfContrat(Contrat $contrat = null, PdfService $pdf)
    {

        $html = $this->renderView('contrat/detaille_contrat.html.twig', [
            'contrat' => $contrat,

        ]);
        $pdf->showPdfFile($html);
    }


    #[Route('/state', name: 'stat', methods: ['GET', 'POST'])]
    public function statistiques(ContratRepository $contratRepository)
    {
        $contrat = $contratRepository->countByPrix();

        $date = [];
        $contratCount = [];

        foreach ($contrat as $com) {
            $date[] = $com['datedebut'];
            $contratCount[] = $com['count'];
        }

        return $this->render('contrat/stats.html.twig', [
            'datedebut' => json_encode($date),
            'contratCount' => json_encode($contratCount),
        ]);
    }

    #[Route('/new', name: 'app_contrat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification que la date de début est antérieure à la date de fin
            if ($contrat->getDatedebut() >= $contrat->getDatefin()) {
                $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
                return $this->redirectToRoute('app_contrat_new');
            }
    
            $entityManager->persist($contrat);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contrat_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ContratType::class, $contrat);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérification que la date de début est antérieure à la date de fin
        if ($contrat->getDatedebut() >= $contrat->getDatefin()) {
            $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
            return $this->redirectToRoute('app_contrat_edit', ['id' => $contrat->getId()]);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('contrat/edit.html.twig', [
        'contrat' => $contrat,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_contrat_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contrat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }
}