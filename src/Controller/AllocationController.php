<?php

namespace App\Controller;
use App\Entity\Allocation;
use App\Form\AllocationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AllocationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AllocationController extends AbstractController
{
    #[Route('/allocation', name: 'app_allocation')]
    public function index(): Response
    {
        return $this->render('allocation/index.html.twig', [
            'controller_name' => 'AllocationController',
        ]);
    }
    #[Route('/adminallocation', name: 'adminallocation')]
    public function indexadmin(AllocationRepository $repo): Response
    {
        $allocation=$repo->findAll();
        return $this->render('adminallocation/index.html.twig',[
            'allocation' => $allocation,
        ]); 
    }
    #[Route('/addformallocation', name: 'addformallocation')]
    public function addformallocation(ManagerRegistry $managerRegistry, Request $req ): Response
    {  $x=$managerRegistry->getManager();  
        $allocation=new allocation();
         $form =$this->createForm(AllocationType::class,$allocation);
         $form->handleRequest($req); 
         if($form->isSubmitted()and $form->isValid()){
            $imageFile = $form['image']->getData();
            
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

               
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
    
          return $this->redirectToRoute('adminallocation');} 
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
          
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

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
public function deleteallocation($id,AllocationRepository $AllocationRepository,ManagerRegistry $managerRegistry): Response
{
    $em=$managerRegistry->getManager();
    $dataid=$AllocationRepository->find($id);
    $em->remove($dataid);
    $em->flush();

return $this->redirectToRoute('adminallocation');  
}

#[Route('/userallocation', name: 'userallocation')]
public function showuserallocation(AllocationRepository $repo): Response
{   
    $allocations=$repo->findAll();
    return $this->render('userallocation/index.html.twig', [
        'controller_name' => 'AllocationController',
        'allocations' => $allocations
    ]);
}
}
