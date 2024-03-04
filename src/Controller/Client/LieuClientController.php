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
    //map
    #[Route('/map', name: 'map_lieu')]
    public function add(Request $request, LieuRepository $lieuRepository): Response
{
    $lieux = $lieuRepository->findAll(); // Récupérer tous les lieux disponibles depuis le repository

    $mapboxAccessToken = 'pk.eyJ1Ijoib3VtYWltYTEyIiwiYSI6ImNsdGJ2OXN6ajFuNHAyaW03M212eTN2NzkifQ.l6QJ1G10Yg6LM8Pmwp6SdQ';

    return $this->render('Client/lieu_client/map.html.twig', [
        'lieux' => $lieux, // Transmettre les lieux disponibles au modèle Twig
        'mapbox_access_token' => $mapboxAccessToken,
    ]);
}

   
   
   


   

}
