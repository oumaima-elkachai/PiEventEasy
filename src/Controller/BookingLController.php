<?php

namespace App\Controller;

use App\Entity\BookingL;
use App\Entity\Lieu;
use App\Form\BookingType;
use App\Repository\BookingLRepository;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BookingLController extends AbstractController
{
    private $flashy;

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }
    #[Route('/booking/{lieuId}', name: 'booking')]
    public function booking(Request $request, EntityManagerInterface $entityManager,BookingLRepository $bookingRepository, $lieuId,FlashyNotifier $flashy): Response
    {
        $lieu = $entityManager->getRepository(Lieu::class)->find($lieuId);

        if (!$lieu) {
            throw $this->createNotFoundException('Le lieu avec l\'ID '.$lieuId.' n\'existe pas.');
        }

        $booking = new BookingL();
        $booking->setLieub($lieu);
        $booking->setPrix($lieu->getPrix());

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // Vérifier s'il existe déjà une réservation pour le même lieu et la même date
             $existingBookings = $bookingRepository->findBy([
                'lieub' => $lieu,
                'DateD' => $booking->getDateD(),
                'DateF' => $booking->getDateF(),
            ]);
             // Vérifier si une réservation existante chevauche la nouvelle réservation
            foreach ($existingBookings as $existingBooking) {
                if ($existingBooking->getDateD() <= $booking->getDateF() && $existingBooking->getDateF() >= $booking->getDateD()) {
                    $this->addFlash('delete','Une réservation existe déjà pour ce lieu et cette période.');
                    return $this->redirectToRoute('booking', [
                        'lieuId' => $lieuId,
                    ]);
            }
            }
            // Enregistrer la nouvelle réservation
            $entityManager->persist($booking);
            $entityManager->flush();
            

            // Rediriger l'utilisateur vers une page de confirmation
            return $this->redirectToRoute('app_cal');
        }

        return $this->render('booking_l/bookingL.html.twig', [
            'form' => $form->createView(),
            'lieu' => $lieu,
            'booking' => $booking,
            'error' => $request->query->get('error'),
        ]);
    }

    // #[Route('/booking_confirmation', name: 'booking_confirmation')]
    // public function confirmation(): Response
    // {
    //     // Afficher un message de confirmation de la réservation
    //     return $this->render('booking_l/confirmation.html.twig');
    // }

    // #[Route('/adminb', name: 'admin_booking')]
    // public function adminbooking(BookingLRepository $bookRepository): Response
    // {
    //     $book = $bookRepository->findAll();
    //     return $this->render('booking_l/booking_details.html.twig', [
    //         'book' => $book
    //     ]);
    // }
    //afficher calendrier au user avec ses reservations
    #[Route('/cal', name: 'app_cal', methods: ['GET'])]
    public function cal(BookingLRepository $appointmentRepository)
    {
        $events = $appointmentRepository->findAll();

        $rdvs = [];

        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDateD()->format('Y-m-d\TH:i:sO'),
                'end' => $event->getDateF()->format('Y-m-d\TH:i:sO'),
                'title' => $event->getLieub()->getNom(),
            ];
        }

     

        $data = json_encode($rdvs);

        return $this->render('booking_l/confirmation.html.twig', compact('data'));
    }
}
