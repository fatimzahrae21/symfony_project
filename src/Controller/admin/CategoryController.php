<?php
namespace App\Controller\admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route("/admin/category" , name: 'admin.category.')]

class CategoryController extends AbstractController {


    #[Route('/' , name: 'index')]
    public function index(CategoryRepository $repository ,EntityManagerInterface $em): Response 
    {
        
        return $this->render('admin/category/index.html.twig' , [
            'categorys' => $repository->findAllWithCount()
        ]);

    }
    #[Route("/create" , name: 'create')]
    public function create(Request $request , EntityManagerInterface $em){

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category , [
            'allow_extra_fields' => true, 
        ]);
         $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category->setCreatedAt(new \DateTimeImmutable());
            $category->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($category);
            $em->flush();
            $this->addFlash('success' , 'La categorie a bien été créée');
            return $this->redirectToRoute('admin.category.index');

        }
        return $this->render('admin/category/create.html.twig' , [
            'form' => $form
        ]);
    }

    #[Route("/{id}" , name: 'edit' , requirements: ['id' => Requirement::DIGITS] , methods: ['GET' , 'POST'])]
    public function edit(Request $request , Category $category, EntityManagerInterface $em){
        $form = $this->createForm(CategoryType::class , $category , [
            'allow_extra_fields' => true, ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $category->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success' , 'La categorie a bien été modifier');
            return $this->redirectToRoute('admin.category.index');

        }
        return $this->render('admin/category/edit.html.twig' , [
            'category' => $category,
            'form' => $form
        ]);

    }
    #[Route("/{id}" , name: 'delete' , requirements: ['id' => Requirement::DIGITS]  , methods: ['DELETE'])]
   
    public function remove(Category $category , EntityManagerInterface $em){


        $em->remove($category);
        $em->flush();
        $this->addFlash('success' , 'La catégory a bien été supprimée');
        return $this->redirectToRoute('admin.category.index');
    }




}