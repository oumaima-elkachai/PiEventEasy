<?php

namespace App\Controller;

use App\Entity\Allocation;
use App\Form\AllocationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AllocationRepository;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Security\Core\Security;


class AllocationController extends AbstractController
{
    // #[Route('/allocation', name: 'app_allocation')]
    // public function index(): Response
    // {
    //     return $this->render('allocation/index.html.twig', [
    //         'controller_name' => 'AllocationController',
    //     ]);
    // }
    #[Route('/adminallocation', name: 'adminallocation')]
    public function indexadmin(AllocationRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $allocation = $repo->findAll();

        $pagination = $paginator->paginate(
            $allocation, // Users query
            $request->query->getInt('page', 1), // Current page
            5 // Items per page
        );


        return $this->render('adminallocation/index.html.twig', [
            'allocation' => $pagination,
        ]);
    }

    //calendrier :
    #[Route('/calendrier', name: 'calendrier', methods: ['GET'])]
    public function calendrier(AllocationRepository $repo)
    {
        // Récupération de tous les événements enregistrés dans la base de données
        $allocations = $repo->findAll();

        // Création d'un tableau de rendez-vous vide
        $rdvs = [];

        // Parcours de tous les événements pour les ajouter au tableau de evenement
        foreach ($allocations as $allocation) {
            // Création d'un tableau pour chaque allocation
            $rdv = [];

            // Ajout de l'identifiant de l'événement au tableau de evenement
            $rdv['id'] = $allocation->getId();

            // Ajout du titre de l'événement au tableau de evenement
            $rdv['title'] = $allocation->getNom();

            // Ajout de la description de l'allocation au tableau de evenement
            $rdv['description'] = $allocation->getPrix();

            // Ajout de la date de début de l'événement au tableau de evenement
            $rdv['start'] = $allocation->getDate()->format('Y-m-d');

            // Ajout de la date de fin de l'événement au tableau de evenement
            $rdv['end'] = $allocation->getDate()->format('Y-m-d');

            // Ajout de la couleur de fond de l'événement au tableau de evenement
            $rdv['backgroundColor'] = '#FF7474';

            // Ajout de la couleur de bordure de l'événement au tableau de evenement
            $rdv['borderColor'] = '#000000';

            // Ajout de la couleur de texte de l'événement au tableau de evenement
            $rdv['textColor'] = '#000000';

            // Désactivation de la modification de l'événement dans le calendrier
            $rdv['editable'] = false;

            // Ajout du tableau de rendez-vous de l'événement au tableau de rendez-vous global
            $rdvs[] = $rdv;
        }

        // Encodage du tableau de rendez-vous global au format JSON
        $data = json_encode($rdvs);

        // Affichage de la page du calendrier avec les données encodées
        return $this->render('adminallocation/calendrier.html.twig', compact('data'));
    }

    //calendrier:
    #[Route('/addformallocation', name: 'addformallocation')]
    public function addformallocation(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $x = $managerRegistry->getManager();
        $allocation = new allocation();
        $form = $this->createForm(AllocationType::class, $allocation);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $imageFile = $form['image']->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $allocation->setImage($newFilename);
            }

            $x->persist($allocation);
            $x->flush();

            return $this->redirectToRoute('adminallocation');
        }
        return $this->render('adminallocation/addformallocation.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/editallocation/{id}', name: 'editallocation')]
    public function editallocation($id, AllocationRepository $allocationRepository, ManagerRegistry $managerRegistry, Request $req): Response
    {
        $x = $managerRegistry->getManager();
        $allocation = $allocationRepository->find($id);

        $form = $this->createForm(AllocationType::class, $allocation);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form['image']->getData();

            if ($imageFile) {

                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $oldFilename = $allocation->getImage();
                if ($oldFilename) {
                    $oldFilePath = $this->getParameter('images_directory') . '/' . $oldFilename;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $allocation->setImage($newFilename);
            }

            $x->persist($allocation);
            $x->flush();

            return $this->redirectToRoute('adminallocation');
        }

        return $this->renderForm('adminallocation/editallocation.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/deletallocation/{id}', name: 'deleteallocation')]
    public function deleteallocation($id, AllocationRepository $AllocationRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $AllocationRepository->find($id);
        $em->remove($dataid);
        $em->flush();

        return $this->redirectToRoute('adminallocation');
    }

    #[Route('/userallocation', name: 'userallocation')]
    public function showuserallocation(AllocationRepository $repo): Response
    {

        $allocations = $repo->findAll();
        return $this->render('userallocation/index.html.twig', [
            'controller_name' => 'AllocationController',
            'allocations' => $allocations
        ]);
    }



    #[Route('/decreasequantity/{id}', name: 'decreasequantity')]
    public function Decreasequantity($id, AllocationRepository $repo, ManagerRegistry $managerRegistry, FlashyNotifier $flashy, Security $security, EventRepository $eventRepository): Response
    {
        $x = $managerRegistry->getManager();
        $currentUser = $security->getUser();

        // Find the allocation by ID
        $allocation = $repo->find($id);

        // Check if allocation exists
        if (!$allocation) {
            throw $this->createNotFoundException('Allocation not found');
        }

        // Check if the allocation already has an associated event
        if ($allocation->getEvent() !== null) {
            $flashy->error('Already reserved!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('userallocation');
        }

        // Retrieve events associated with the current user
        $userEvents = $eventRepository->findBy(['userid' => $currentUser]);

        // Check if the allocation exists for any of the user's events
        foreach ($userEvents as $event) {
            foreach ($event->getAllocation() as $eventAllocation) {
                if ($eventAllocation === $allocation) {
                    // Check if the quantity is greater than zero
                    if ($allocation->getQuantity() <= 0) {
                        $flashy->error('No more available places!', 'http://your-awesome-link.com');
                        return $this->redirectToRoute('userallocation');
                    }
                }
            }
        }

        // If the user has events, assign the first event to the allocation
        if (!empty($userEvents)) {
            $event = $userEvents[0]; // Assuming the user has at least one event
            $allocation->setEvent($event);
            $x->persist($allocation);
            $x->flush();
            $flashy->success('Event assigned successfully!', 'http://your-awesome-link.com');
        } else {
            // Handle case where user doesn't have any events
            $flashy->error('No events found for the current user!', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('userallocation');
    }
    #[Route('/uptorent/{id}', name: 'uptorent')]
    public function uptorent($id, AllocationRepository $allocationRepository, ManagerRegistry $managerRegistry, Request $req): Response
    {
        $x = $managerRegistry->getManager();
        $allocation = $allocationRepository->find($id);

        $allocation->setEvent(NULL);

        $x->persist($allocation);
        $x->flush();

        return $this->redirectToRoute('adminallocation');
    }
}
