<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recipe.index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
       $recipes = $repository->findAll();

        // $recipes = $repository->findWithDurationLowerThan(5);
//-----------------ajouter---------------------------------------
//         $recipe = new Recipe();
//         $recipe->setTitle('Pàte bolognaise')
//             ->setSlug('pate-bolognaise' . uniqid()) // Slug unique
//             ->setContent('La pâte bolognaise est un plat traditionnel italien, originaire de la ville de Bologne, en Italie. Ce mets savoureux est une sauce riche et onctueuse, composée principalement de viande hachée, de tomates, de carottes, doignons, de céleri, et dherbes aromatiques. La sauce est souvent mijotée pendant plusieurs heures pour développer une profondeur de saveur. Elle est traditionnellement servie avec des pâtes telles que les tagliatelles, bien que dautres types de pâtes, comme les spaghettis, soient également populaires.

// La bolognaise est un plat réconfortant, parfait pour les repas familiaux ou les occasions spéciales. Elle peut être adaptée selon les préférences en ajoutant du vin rouge ou du lait, pour un goût encore plus riche et délicat.')
//             ->setContenu('iii')
//             ->setDuration(8)
//             ->setCreatedAt(new \DateTimeImmutable())
//             ->setUpdatedAt(new \DateTimeImmutable());

//         // Ajouter à la gestion de Doctrine et sauvegarder
//         $em->persist($recipe); // added from chat gpt 
//         $em->flush();
//---------------suppremer-----------------------------------
        // $em-> remove($recipes[1]);
        // $em->flush();
//-------------------
  //dd($repository->findTotalDuration());
//---------------modifier------------------------------
// $recipes[0]->setTitle('roz ou l7lib');
// $em->flush();


        return $this->render(
            'recipe/index.html.twig',
            [
                'recipes' =>  $recipes
            ]
        );
    }

    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    {
        $recipe = $repository->find($id);
        if ($recipe->getSlug() != $slug) {
            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe



        ]);
    }
    #[Route('/recette/{id}/edit' , name: 'recipe.edit')]
    public function edit(Recipe $recipe , Request $request , EntityManagerInterface $em){
        $form =  $this->createForm(RecipeType::class , $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $em ->flush();
            $this->addFlash('success' , 'La recette a bien ete modifier');

            return $this->redirectToRoute('recipe.index');

        }
        return $this->render('recipe/edit.html.twig' , [
            'recipe' => $recipe ,
            'form' => $form

        ]);
    }
     
    #[Route('/recette/create' , name: 'recipe.create')]
    public function create(Request $request , EntityManagerInterface $em){

        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class , $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recipe->setCreatedAt(new \DateTimeImmutable());
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success' , 'la recette a bien ete ajouter');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/create.html.twig' , [
            'form' =>$form
        ]);
    }

    #[Route('/recette/{id}' , name: 'recipe.delete' )]

    public function remove(Recipe $recipe , EntityManagerInterface $em)
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success' , 'La recette a bien ete supprimée');
        return $this->redirectToRoute('recipe.index');
    }

}


// #[Route('/recette/{slug}-{id}', name: 'recipe.show' )]
// public function index(Request $request , string $slug , int $id): Response
// {
//    dd($slug , $id);
// }