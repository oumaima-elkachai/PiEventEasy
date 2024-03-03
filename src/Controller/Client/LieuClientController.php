<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Repository\LieuRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class LieuClientController extends AbstractController
{


   
    #[Route('/lieufront', name: 'app_lieu_client',methods:['GET'])]
    public function showdbauthor(LieuRepository $lieuRepository, PaginatorInterface $paginator,Request $request ): Response
    {
        $lieu= $lieuRepository->findAll();
        $pagination = $paginator->paginate(
            $lieu, // Query results
            $request->query->getInt('page', 1), // Current page number, default to 1
            3// Items per page
        );
        return $this->render('Client/lieu_client/index.html.twig', [
            'l' => $lieu,
            'pagination' => $pagination,

        ]);
    }
   
   
   


   

}
