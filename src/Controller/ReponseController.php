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
            if ($reclamation->getUser() !== null) {
                // Envoi de l'e-mail avec Swift Mailer
                $email = (new Email())
                    ->from('eventeasy@gmail.com')
                    ->to($reclamation->getUser()->getEmail())
                    ->subject('Réponse à votre réclamation')
                    ->text($reponse->getDescription());
            
                $mailer->send($email);
            
                return new Response('Réclamation créée avec succès et e-mail envoyé à : ' . $reclamation->getUser()->getEmail());
            } else {
                // Gérez le cas où l'utilisateur associé à la réclamation est null
                // Vous pouvez choisir de ne pas envoyer d'e-mail ou de prendre d'autres mesures.
                return new Response('Réclamation créée avec succès, mais l\'utilisateur associé est null.');
            }
        }

        return $this->render('reponse/repondre_reclamation.html.twig', [
            'form' => $form->createView(),
            'reclamation' => $reclamation
        ]);
    }
}
