<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Repository\LieuRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuClientController extends AbstractController
{
   
    #[Route('/lieufront', name: 'app_lieu_client')]
    public function showdbauthor(LieuRepository $lieuRepository): Response
    {
        $lieu= $lieuRepository->findAll();
        return $this->render('Client/lieu_client/index.html.twig', [
            'l' => $lieu
        ]);
    }
    #[Route('/searchplaces', name: 'search_places')]
    public function search(Request $request, LieuRepository $lieuRepository)
    {
        $keyword = $request->query->get('keyword');

        // Recherche des lieux par nom, catégorie ou lieu
        $lieu = $lieuRepository->search($keyword);

        return $this->render('Client/lieu_client/search.html.twig', [
            'lieu' => $lieu,
            'keyword' => $keyword
        ]);
    }
   


   

}
