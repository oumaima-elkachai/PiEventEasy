<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Event;
use App\Form\EventType;
use App\Entity\CategoryE;
use App\Repository\UserRepository;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
    #[Route('/add/{id}', name: 'add')]
    public function add( $id,ManagerRegistry $managerRegistry,Request $req, UserRepository $UserRepository): Response
    { 
        $x=$managerRegistry->getManager();
        $user=$UserRepository->find($id);
        $event=new Event();//instance 
        $event->setUserid($user);
        $form = $this->createForm(EventType::class,$event);
        $form->handleRequest($req);
        
        if($form->isSubmitted() and $form->isValid() ){
        $x->persist($event);
        $x->flush();
        return $this->redirectToRoute('show');
        }
        return $this->renderForm('client/add.html.twig', [
            
            'form'=>$form
        ]);
    }
    #[Route('/edit/{id}', name: 'edit')]
    public function edit( $id,ManagerRegistry $managerRegistry,Request $req, EventRepository $EventRepository): Response   
     {{$x= $managerRegistry->getManager();
        $detaid=$EventRepository->find($id);
        //var_dump($detail).die
        $form = $this->createForm(EventType::class,$detaid);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid() ){
            $x->persist($detaid);
            $x->flush();
            return $this->redirectToRoute('show');
        }
       
        return $this->renderForm('client/edit.html.twig', [
            'form'=>$form        ]
        );
    }
    }
    #[Route('/show', name: 'show')]
    public function show(EventRepository $EventRepository): Response
    {
        $event= $EventRepository->findAll();
        return $this->render('client/show.html.twig', [
           'evenement'=>$event
        ]);
    }
     
    #[Route('/delete/{id}', name: 'delete')]
    public function delete( $id,ManagerRegistry $managerRegistry,Request $req, EventRepository $EventRepository): Response   
     {{$x= $managerRegistry->getManager();
        $detaid=$EventRepository->find($id);
        $x->remove($detaid);
        //var_dump($detail).die
        
            $x->flush();
            return $this->redirectToRoute('add');
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
        $event= $EventRepository->findAll();
        return $this->render('admin/showB.html.twig', [
           'evenement'=>$event
        ]);
    }
    #[Route('/editB/{id}', name: 'editB')]
    public function editB( $id,ManagerRegistry $managerRegistry,Request $req, EventRepository $EventRepository): Response   
     {{$x= $managerRegistry->getManager();
        $detaid=$EventRepository->find($id);
        //var_dump($detail).die
        $form = $this->createForm(EventType::class,$detaid);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid() ){
            $x->persist($detaid);
            $x->flush();
            return $this->redirectToRoute('showB');
        }
       
        return $this->renderForm('Admin/editB.html.twig', [
            'form'=>$form        ]
        );
    }
    }
    #[Route('/deleteB/{id}', name: 'deleteB')]
    public function deleteB( $id,ManagerRegistry $managerRegistry,Request $req, EventRepository $EventRepository): Response   
     {{$x= $managerRegistry->getManager();
        $detaid=$EventRepository->find($id);
        $x->remove($detaid);
        //var_dump($detail).die
        
            $x->flush();
            return $this->redirectToRoute('showB');
        }
    
    } 


}

