<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recipe.index' )]
   public function index(Request $request , RecipeRepository $repository): Response
   {
            $recipes = $repository->findAll();
         
            // $recipes = $repository->findWithDurationLowerThan(5);

            return $this->render('recipe/index.html.twig' , 
        [
            'recipes' =>  $recipes
        ]);
   }

   #[Route('/recette/{slug}-{id}' , name: 'recipe.show' , requirements: ['id' => '\d+' , 'slug' =>'[a-z0-9-]+'])]
   public function show(Request $request , string $slug , int $id , RecipeRepository $repository): Response
   {
    $recipe = $repository -> find($id);
    if ($recipe -> getSlug() != $slug){
        return $this -> redirectToRoute('recipe.show' , ['slug' => $recipe -> getSlug() , 'id' => $recipe -> getId()]);
    }
    return $this->render('recipe/show.html.twig' , [
        'recipe' => $recipe
        
        
      
    ]);
   }

}


// #[Route('/recette/{slug}-{id}', name: 'recipe.show' )]
// public function index(Request $request , string $slug , int $id): Response
// {
//    dd($slug , $id);
// }