<?php

namespace App\Controller;

use App\Entity\CategoryL;
use App\Form\CategorylType;
use App\Repository\CategoryLRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorylAdminController extends AbstractController
{
    #[Route('/showcatl', name: 'showcategoryl')]
    public function showcat(): Response
    {
        return $this->render('Admin/categoryl_admin/index.html.twig');
    }
    #[Route('/showdbcat', name: 'showdbcategory')] //affichage
    public function showdbauthor(CategoryLRepository $catRepository): Response
    {

        $cat=$catRepository->findAll();
        return $this->render('Admin/categoryl_admin/showdbcategory.html.twig', [
            'cat'=>$cat

        ]);
    }
    #[Route('/addformcat', name: 'addformCat')]
    public function addformcat(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $x = $managerRegistry->getManager();
        $cat = new CategoryL();
        $form = $this->createForm(CategorylType::class, $cat);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $x->persist($cat);
            $x->flush();

            return $this->redirectToRoute('showdbcategory');
        }
        return $this->renderForm('Admin/categoryl_admin/addCategory.html.twig', [
            'f' => $form
        ]);
    }
    #[Route('/deletecat/{id}', name: 'deletecat')]
    public function deleteroom($id, ManagerRegistry $managerRegistry, CategoryLRepository $catRepository): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $catRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showdbcategory');
    }
}
