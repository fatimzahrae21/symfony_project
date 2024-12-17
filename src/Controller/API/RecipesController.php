<?php

namespace App\Controller\API;

use App\DTO\PaginationDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RecipesController extends AbstractController
{
 #[Route('/api/recipes' , methods: ['GET'])]
 public function index(RecipeRepository $repository ,
  Request $request 
  )
 {
   

    $recipes = $repository->paginateRecipes($request);
    return $this->json($recipes , 200, [], [
        'groups' => ['recipes.index']

    ]);
  

}
#[Route('/api/recipes/{id}' , requirements: ['id' => Requirement::DIGITS])]
public function show(Recipe $recipe)
{
   return $this->json($recipe , 200, [], [
       'groups' => ['recipes.index' , 'recipes.show']

   ]);
}

#[Route('/api/recipes/create' , methods: ['GET', 'POST'])]
public function create(Request $request ,
#[MapRequestPayload(
    serializationContext: [
        'groups' => ['recipes.create']
    ]
)]
    Recipe $recipe ,
    EntityManagerInterface $em
)
{
    $recipe->setCreatedAt(new \DateTimeImmutable());
    $recipe->setUpdatedAt(new \DateTimeImmutable());
    $em->persist($recipe);
    $em->flush();
    return $this->json($recipe , 200, [], [
        'groups' => ['recipes.index' , 'recipes.show']
 
    ]);

}
}