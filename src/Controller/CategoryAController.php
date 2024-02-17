<?php

namespace App\Controller;
use App\Entity\CategoryA;
use App\Form\CategoryAType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryARepository;
use Symfony\Component\HttpFoundation\Request;
class CategoryAController extends AbstractController
{
    #[Route('/category_a', name: 'category_a')]
    public function index(): Response
    {
        return $this->render('category_a/index.html.twig', [
            'controller_name' => 'CategoryAController',
        ]);
    }
    #[Route('/category_a', name: 'category_a')]
    public function showcategoryA(CategoryARepository $repo): Response
    {
        $categorya=$repo->findAll();
        return $this->render('category_a/index.html.twig',[
            'categorya' => $categorya,
        ]); 
    }
    #[Route('/addformcategory', name: 'addformcategory')]
    public function addformallocation(ManagerRegistry $managerRegistry, Request $req ): Response
    {  $x=$managerRegistry->getManager();  
        $categorya=new CategoryA();
         $form =$this->createForm(CategoryAType::class,$categorya);
         $form->handleRequest($req); 
         if($form->isSubmitted()and $form->isValid()){
           
         $x->persist($categorya);
          $x->flush();
          return $this->redirectToRoute('category_a');
        }
        return $this->renderForm('category_a/addformcategory.html.twig', [
            'form' => $form
        ]);

}
#[Route('/deletecategory/{id}', name: 'deletecategory')]
public function deletecategory($id,CategoryARepository $CategoryARepository,ManagerRegistry $managerRegistry): Response
{
    $em=$managerRegistry->getManager();
    $dataid=$CategoryARepository->find($id);
    $em->remove($dataid);
    $em->flush();

return $this->redirectToRoute('category_a');  
}
}