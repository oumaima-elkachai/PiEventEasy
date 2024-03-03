<?php

namespace App\Controller;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
class RegistrationController extends AbstractController
{
    private $passwordEncoder;//hashage mdp

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }



    #[Route('/signup', name: 'app_signup')]
    public function signup(ManagerRegistry $managerRegistry, Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
           
            $user->setRoles(['ROLE_USER']);

            // Save
            $em = $managerRegistry->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/signupUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('registration/contact.html.twig'
            
        );
    }

   

    #[Route('/login', name: 'app_login')]
    public function indexAdmin(ManagerRegistry $managerRegistry, Request $request): Response
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        return $this->render('Admin/indexUs.html.twig', [
            'controller_name' => 'RegistrationController',
            
            
        ]);
    }

    #[Route('/Admin', name: 'app_admin')]
    public function indexAadmin(): Response
    {

        return $this->render(view:'baseAdmin.html.twig'
            
    );
    }


   

    #[Route('/delet/{id}', name: 'deletadmin')]
    public function deletauthor( $id,ManagerRegistry $managerRegistry,Request $req,UserRepository $UserRepository ): Response   
     {{$x= $managerRegistry->getManager();
        $detaid=$UserRepository->find($id);
        $x->remove($detaid);
        //var_dump($detail).die
        
            $x->flush();
            return $this->redirectToRoute('app_signup');
        }
    
    }

    #[Route('/Client', name: 'app_Client')]
    public function indexclient(Request $request): Response
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        return $this->render('base.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/signupF', name: 'app_signup_front')]
    public function signupFront(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new user's password
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set default role for front-end users
            $user->setRoles(['ROLE_USER']);
            $user->setEnabled(true);

            // Save the user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect user after signup
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('registration/signupUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   /* #[Route('/loginc', name: 'app_loginc')]
    public function indexC(ManagerRegistry $managerRegistry, Request $request): Response
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            return $this->redirectToRoute('');
        }
        
        return $this->render('Client/index2.html.twig', [
            'controller_name' => 'RegistrationController',
            'form'=>$form->createView()
            
        ]);
        
    }*/
    
    #[Route('/profile', name: 'profile')]
    public function profile(SessionInterface $session): Response
    {
        // Retrieve user data from the session
        $user = $session->get('user');

        // Render the profile page template with user data
        return $this->render('Client/index2.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/edituser/{id}', name: 'edituser')]
public function edituser($id, ManagerRegistry $managerRegistry, Request $req, UserRepository $UserRepository, UserPasswordEncoderInterface $passwordEncoder): Response
{
    $entityManager = $managerRegistry->getManager();
    $detaid = $UserRepository->find($id);

    if (!$detaid) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    $form = $this->createForm(UserType::class, $detaid);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();
        $hashedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $entityManager->persist($detaid);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin');
    }

    return $this->render('Client/index3.html.twig', [
        'form' => $form->createView()
    ]);
}
        
#[Route('/deleteuser/{id}', name: 'deleteuser')]
public function deleteUser($id, SessionInterface $session): Response
{
    // The $id parameter is automatically resolved from the route parameters
    
    $userRepository = $this->getDoctrine()->getRepository(User::class);

    // Find the user by ID
    $user = $userRepository->find($id);

    // Check if the user exists
    if (!$user) {
        throw $this->createNotFoundException('User not founddddd');
    }

    // Delete the user
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($user);
    $entityManager->flush();

    // Clear the session
    $session->invalidate();

    // Redirect to an appropriate page after deletion
    // For example, redirect to the user list page
    return $this->redirectToRoute('app_login');
}  

#[Route('/useraccounts', name: 'app_useraccounts')]
    public function showdb(UserRepository $userRepository, Request $request): Response
    {
        $user = $userRepository->findAll();
        
        return $this->render('admin/useraccounts.html.twig', [
            'user' => $user
        ]);
    }
#[Route('/adminblockuser/{id}', name: 'app_adminblockuser')]
    public function adminblockuser($id, UserRepository $userRepository, ManagerRegistry $managerRegistry ): Response
    {
        $em = $managerRegistry->getManager();
        $data = $userRepository->find($id);
        $data->setEnabled(!$data->isEnabled());
        $em->persist($data);
        $em->flush();
        return $this->redirectToRoute('app_useraccounts');
    }  


    
    

    

    
}
