<?php

namespace App\Controller;

use App\Entity\BookingL;
use App\Entity\Lieu;
use App\Form\BookingType;
use App\Repository\BookingLRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BookingLController extends AbstractController
{
    #[Route('/booking/{lieuId}', name: 'booking')]
    public function booking(Request $request, EntityManagerInterface $entityManager, $lieuId): Response
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
            $entityManager->persist($booking);
            $entityManager->flush();

            // Rediriger l'utilisateur vers une page de confirmation
            return $this->redirectToRoute('booking_confirmation');
        }

        return $this->render('booking_l/bookingL.html.twig', [
            'form' => $form->createView(),
            'lieu' => $lieu,
            'booking' => $booking,
        ]);
    }

    #[Route('/booking_confirmation', name: 'booking_confirmation')]
    public function confirmation(): Response
    {
        // Afficher un message de confirmation de la réservation
        return $this->render('booking_l/confirmation.html.twig');
    }

    #[Route('/adminb', name: 'admin_booking')]
    public function adminbooking(BookingLRepository $bookRepository): Response
    {
        $book = $bookRepository->findAll();
        return $this->render('booking_l/booking_details.html.twig', [
            'book' => $book
        ]);
    }
}
