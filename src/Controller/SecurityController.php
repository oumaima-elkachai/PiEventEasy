<?php

namespace App\Controller;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
class SecurityController extends AbstractController
{
    private $passwordEncoder;//hashage mdp

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',     
        ]);
    }

    
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         //if ($this->getUser()) {
          //   return $this->redirectToRoute('target_path');
         //}

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(UserType::class);
        return $this->render('security/login.html.twig', 
        ['last_username' => $lastUsername,
         'error' => $error,
         'form' => $form->createView()
        ]);
  
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): RedirectResponse
    {
return $this->redirectToRoute(route:"app_signup_front");
}


#[Route('/editAdmin/{id}', name: 'app_edit')]
public function editadmin($id, ManagerRegistry $managerRegistry, Request $req, UserRepository $UserRepository, UserPasswordEncoderInterface $passwordEncoder): Response
{
    $entityManager = $managerRegistry->getManager();
    $detaid = $UserRepository->find($id);

    if (!$detaid) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    $form = $this->createForm(UserType::class, $detaid);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        // Hacher le mot de passe
        $user = $form->getData();
        $hashedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $entityManager->persist($detaid);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    return $this->render('Admin/index1.html.twig', [
        'form' => $form->createView()
    ]);
}
#[Route('/editFront/{id}', name: 'app_editf')]
public function ed($id, ManagerRegistry $managerRegistry, Request $req, UserRepository $UserRepository, UserPasswordEncoderInterface $passwordEncoder): Response
{
    $entityManager = $managerRegistry->getManager();
    $detaid = $UserRepository->find($id);

    if (!$detaid) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    $form = $this->createForm(UserType::class, $detaid);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        // Hacher le mot de passe
        $user = $form->getData();
        $hashedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $entityManager->persist($detaid);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    return $this->render('Admin/profil.html.twig', [
        'form' => $form->createView()
    ]);
}
}

