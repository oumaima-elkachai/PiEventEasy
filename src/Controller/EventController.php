<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use App\Entity\CategoryE;
use App\Repository\LieuRepository;
use App\Repository\UserRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Service\TwilioSMSService;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EventController extends AbstractController
{
    // #[Route('/event', name: 'app_event')]
    // public function index(): Response
    // {
    //     return $this->render('event/index.html.twig', [
    //         'controller_name' => 'EventController',
    //     ]);
    // }

    #[Route('/add', name: 'add')]
    public function add(Security $security, ManagerRegistry $managerRegistry, Request $request, UserRepository $userRepository, LieuRepository $LieuRepository): Response
    {
        // Retrieve user from security
        $user = $security->getUser();

        // Check if $user is an instance of your User entity
        if (!$user instanceof User) {
            throw new \LogicException('User object is not an instance of User entity.');
        }

        // Use user's ID
        $userId = $user->getId();

        // Find user by ID
        $user = $userRepository->find($userId);

        // Create new Event
        $event = new Event();
        $event->setUserid($user);
        $lieu = $LieuRepository->find("1");
        $event->setLieu($lieu);
        $event->setEmail($user->getEmail());
        $event->setPhone($user->getPhonenumber());

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('showbyid', ['userid' => $user->getId()]);
        }

        return $this->renderForm('client/add.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id, ManagerRegistry $managerRegistry, Request $req, EventRepository $EventRepository): Response
    { {
            $x = $managerRegistry->getManager();
            $detaid = $EventRepository->find($id);
            $event = $detaid;
            $user = $event->getUserid()->getId();

            //var_dump($detail).die
            $form = $this->createForm(EventType::class, $detaid);
            $form->handleRequest($req);
            if ($form->isSubmitted() and $form->isValid()) {
                $x->persist($detaid);
                $x->flush();
                return $this->redirectToRoute('showbyid', ['userid' => $user]);
            }

            return $this->renderForm(
                'client/edit.html.twig',
                [
                    'form' => $form
                ]
            );
        }
    }
    /*  #[Route('/show', name: 'show')]
    public function show(EventRepository $EventRepository): Response
    {
        $event= $EventRepository->findAll();
        return $this->render('client/show.html.twig', [
           'evenement'=>$event
        ]);
    }*/

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, ManagerRegistry $managerRegistry, Request $req, EventRepository $EventRepository): Response
    { {
            $x = $managerRegistry->getManager();
            $detaid = $EventRepository->find($id);
            $event = $detaid;
            $user = $event->getUserid()->getId();
            $x->remove($detaid);
            //var_dump($detail).die

            $x->flush();
            return $this->redirectToRoute('showbyid', ['userid' => $user]);
        }
    }
    #[Route('/showbyid/{userid}', name: 'showbyid')]
    public function showbyid($userid, EventRepository $eventRepository, ManagerRegistry $managerRegistry): Response
    {
        $events = $eventRepository->findBy(['userid' => $userid]);

        return $this->render('client/showbyid.html.twig', [
            'evenement' => $events,
        ]);
    }
    #[Route('/showB', name: 'showB')]
    public function showB(EventRepository $EventRepository): Response
    {
        $event = $EventRepository->findAll();
        return $this->render('admin/showB.html.twig', [
            'evenement' => $event
        ]);
    }
    #[Route('/editB/{id}', name: 'editB')]
    public function editB($id, ManagerRegistry $managerRegistry, Request $req, EventRepository $EventRepository): Response
    { {
            $x = $managerRegistry->getManager();
            $detaid = $EventRepository->find($id);
            //var_dump($detail).die
            $form = $this->createForm(EventType::class, $detaid);
            $form->handleRequest($req);
            if ($form->isSubmitted() and $form->isValid()) {
                $x->persist($detaid);
                $x->flush();
                return $this->redirectToRoute('showB');
            }

            return $this->renderForm(
                'Admin/editB.html.twig',
                [
                    'form' => $form
                ]
            );
        }
    }
    #[Route('/deleteB/{id}', name: 'deleteB')]
    public function deleteB($id, ManagerRegistry $managerRegistry, Request $req, EventRepository $EventRepository): Response
    { {
            $x = $managerRegistry->getManager();
            $detaid = $EventRepository->find($id);
            $x->remove($detaid);
            //var_dump($detail).die

            $x->flush();
            return $this->redirectToRoute('showB');
        }
    }
    // composer require dompdf/dompdf


    #[Route('/generate-pdf', name: 'generate_pdf')]
    public function generatePdf(EventRepository $eventRepository): Response
    {
        // Récupérer les données depuis le repository
        $demandes = $eventRepository->findAll();

        // Créer une instance de Dompdf
        $dompdf = new Dompdf();

        // Générer le contenu HTML du PDF
        $html = $this->renderView('admin/pdf.html.twig', [
            'demandes' => $demandes,
        ]);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // (Optionnel) Définir les options de rendu
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf->setOptions($options);

        // Rendre le PDF
        $dompdf->render();

        // Envoyer le PDF en réponse
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="demandes.pdf"');

        return $response;
    }
    #[Route('/sendSms/{EventId}', name: 'sendSms')]
    public function sendSms(EventRepository $EventRepository, $EventId): Response
    {
        // Retrieve the application entity
        $Event = $EventRepository->find($EventId);

        if (!$Event) {
            throw $this->createNotFoundException('Event not found');
        }

        // Extract necessary information from the application and offer entities


        $firstName = $Event->getUserid()->getFname();
        $lastName = $Event->getUserid()->getLname();
        $phoneNumber = '+216' . $Event->getPhone();
        $date = $Event->getDate()->format('Y-m-d'); // Format the date as 'YYYY-MM-DD'
        // Construct the message
        $message = "WELCOME TO EVENTEASY , Dear $firstName $lastName,\nCongratulations! Your Event  has been accepted on $date ";

        // Create an instance of TwilioSMSService manually and pass the required parameters
        $twilioSMSService = new TwilioSMSService(
            $this->getParameter('twilio_account_sid'),
            $this->getParameter('twilio_auth_token'),
            $this->getParameter('twilio_phone_number')
        );

        // Send SMS
        $twilioSMSService->sendSMS($phoneNumber, $message);

        return $this->redirectToRoute('showbyid', ['userid' => $Event->getUserid()->getId()]);
    }
}
