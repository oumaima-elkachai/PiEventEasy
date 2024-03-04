<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReponseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class ReponseController extends AbstractController
{
    /**
     * @Route("/repondre/{reclamationId}", name="repondre_reclamation")
     */
    public function repondreReclamation(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, int $reclamationId, Security $security): Response
    {
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($reclamationId);

        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation non trouvée');
        }

        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour répondre à une réclamation.');
        }

        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setReclamation($reclamation); // Assurez-vous que votre entité Reponse a une méthode setReclamation
            // Votre logique ici. Pas besoin de recréer la Reclamation.

            $entityManager->persist($reponse);
            $entityManager->flush();

            // Envoi de l'e-mail
            $email = (new Email())
                ->from('eventeasy@gmail.com')
                ->to($reclamation->getUser()->getEmail()) // Directement depuis l'instance de Reclamation
                ->subject('Réponse à votre réclamation')
                ->text($reponse->getDescription());

            $mailer->send($email);

            // Rediriger vers une page ou afficher un message de confirmation
            return $this->redirectToRoute('nom_de_la_route_pour_confirmation');
        }

        return $this->render('reponse/repondre_reclamation.html.twig', [
            'form' => $form->createView(),
            'reclamation' => $reclamation
        ]);
    }
}
