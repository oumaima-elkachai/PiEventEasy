<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\AddlType;
use App\Repository\LieuRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuAdminController extends AbstractController
{
    #[Route('/showdbl', name: 'showdblieu')]
    public function showdblieu(LieuRepository $lieuRepository): Response
    {
        $l = $lieuRepository->findAll();
        return $this->render('Admin/lieu_admin/index.html.twig', [
            'l' => $l
        ]);
    }
    
    #[Route('/addforml', name: 'addforml')]
    public function addforml(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $x = $managerRegistry->getManager();
        $l = new Lieu();
        $form = $this->createForm(AddlType::class, $l);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $photoFile = $form->get('image')->getData();

            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('image_directory'), // Specify the directory where photos should be uploaded
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                }

                // Update the photo path in the user entity
                $l->setImage($newFilename);
            }
            $x->persist($l);
            $x->flush();

            return $this->redirectToRoute('showdblieu');
        }
       $mapboxAccessToken = 'pk.eyJ1Ijoib3VtYWltYTEyIiwiYSI6ImNsdGJ2OXN6ajFuNHAyaW03M212eTN2NzkifQ.l6QJ1G10Yg6LM8Pmwp6SdQ';

        return $this->renderForm('Admin/lieu_admin/lieuform.html.twig', [
            'f' => $form,
            'mapbox_access_token' => $mapboxAccessToken,
            
        ]);
    }
    
    #[Route('/editl/{id}', name: 'editlieu')]
    public function editroom($id,LieuRepository $lieuRepository, ManagerRegistry $managerRegistry,Request $req): Response
    {

        //var_dump($id) . die();
        $x = $managerRegistry->getManager();
        $dataid = $lieuRepository->find($id);
        // var_dump($dataid) . die();
        $form = $this->createForm(AddlType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $photoFile = $form->get('image')->getData();

            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('image_directory'), // Specify the directory where photos should be uploaded
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                }

                // Update the photo path in the service entity
                $dataid->setImage($newFilename);
            }

            $x->persist($dataid);
            $x->flush();
            return $this->redirectToRoute('showdblieu');
        }
        $mapboxAccessToken = 'pk.eyJ1Ijoib3VtYWltYTEyIiwiYSI6ImNsdGJ2OXN6ajFuNHAyaW03M212eTN2NzkifQ.l6QJ1G10Yg6LM8Pmwp6SdQ';

        return $this->renderForm('Admin/lieu_admin/lieuform.html.twig', [
            'f' => $form,
            'mapbox_access_token' => $mapboxAccessToken,
        ]);
    }  

    #[Route('/deletel/{id}', name: 'deletel')]
    public function deletelieu($id, ManagerRegistry $managerRegistry, LieuRepository $lieuRepository): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $lieuRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showdblieu');
    }
    
//// ajout d'un lieu avec map 
 
// #[Route('/lieu/add', name:'lieu_add')]

// public function addl(Request $request): Response
// {
//     $doctor = new Lieu();

//     $form = $this->createForm(AddlType::class, $doctor);

//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         $entityManager = $this->getDoctrine()->getManager();
//         $entityManager->persist($doctor);
//         $entityManager->flush();

//         return $this->redirectToRoute('showdblieu');
//     }

//     $mapboxAccessToken = 'pk.eyJ1Ijoib3VtYWltYTEyIiwiYSI6ImNsdGJ2OXN6ajFuNHAyaW03M212eTN2NzkifQ.l6QJ1G10Yg6LM8Pmwp6SdQ';

//     return $this->render('Admin/lieu_admin/lieuform.html.twig', [
//         'f' => $form->createView(),
//         'mapbox_access_token' => $mapboxAccessToken,
//     ]);
// }






}
