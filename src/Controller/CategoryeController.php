<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategoryE;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryERepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\CategoryEType;

class CategoryeController extends AbstractController
{
    #[Route('/categorye', name: 'app_categorye')]
    public function index(): Response
    {
        return $this->render('categorye/index.html.twig', [
            'controller_name' => 'CategoryeController',
        ]);
    }
    #[Route('/addCB', name: 'addCB')]
    public function add( ManagerRegistry $managerRegistry,Request $req): Response
    { 
        $x=$managerRegistry->getManager();
        $category=new CategoryE();
        $form = $this->createForm(CategoryEType::class,$category);
        $form->handleRequest($req);
        
        if($form->isSubmitted() and $form->isValid() ){
        $x->persist($category);
        $x->flush();
        return $this->redirectToRoute('showCB');
        }
        return $this->renderForm('admin/addCB.html.twig', [
            
            'form'=>$form
        ]);
    }
    #[Route('/showCB', name: 'showCB')]
    public function showCB(CategoryERepository $CategoryERepository): Response
    {
        $category= $CategoryERepository->findAll();
        return $this->render('admin/showCB.html.twig', [
           'category'=>$category
        ]);
    }
    #[Route('/editCB/{id}', name: 'editCB')]
    public function edit( $id,ManagerRegistry $managerRegistry,Request $req, CategoryERepository $CategoryERepository): Response   
     {{$x= $managerRegistry->getManager();
        $detaid=$CategoryERepository->find($id);
        //var_dump($detail).die
        $form = $this->createForm(CategoryEType::class,$detaid);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid() ){
            $x->persist($detaid);
            $x->flush();
            return $this->redirectToRoute('showCB');
        }
       
        return $this->renderForm('admin/editCB.html.twig', [
            'form'=>$form        ]
        );
    }
    }
    #[Route('/deleteCB/{id}', name: 'deleteCB')]
    public function delete( $id,ManagerRegistry $managerRegistry,Request $req, CategoryERepository $CategoryERepository): Response   
     {{$x= $managerRegistry->getManager();
        $detaid=$CategoryERepository->find($id);
        $x->remove($detaid);
        //var_dump($detail).die
        
            $x->flush();
            return $this->redirectToRoute('showCB');
        }
    
    } 
}
