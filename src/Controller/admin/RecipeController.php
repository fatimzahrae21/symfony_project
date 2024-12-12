<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

  #[IsGranted('ROLE_ADMIN')]
class RecipeController extends AbstractController
{
    #[Route('/admin/recipe', name: 'admin.recipe.index', methods: ['GET'])]
  
    
    public function index(RecipeRepository $repository, CategoryRepository $categoryRepository ,EntityManagerInterface $entityManager): Response
    {
        
        
        // $this->denyAccessUnlessGranted('ROLE_USER');
        $recipes = $repository->findWithDurationLowerThan(100);
        return $this->render(
            'admin/recipe/index.html.twig',
            [
                'recipes' =>  $recipes
            ]
        ); 
          // $category = (new Category())
        // ->setUpdatedAt(new \DateTimeImmutable())
        // ->setCreatedAt(new \DateTimeImmutable())
        // ->setName('demo')
        // ->setName('demo');
        // $entityManager->persist($category);
        // $recipes[0]->setCategory($category);
        // $entityManager->flush();
        // $platPrincip = $categoryRepository->findOneBy(['slug'=>'plat-principale']);
        // $pates = $repository->findOneBy(['slug'=>'riz-et-lait']);
        // $pates->setCategory($platPrincip);
        // $entityManager->flush();
       
      //  $recipes = $repository->findAll();
        // $recipes[0]->getCategory()->getName();
        // dd($recipes[0]->getCategory());
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
        // $em-> remove($recipes[17]);
        // $em->flush();
//-------------------
  //dd($repository->findTotalDuration());
//---------------modifier------------------------------
// $recipes[0]->setTitle('roz ou l7lib');
// $em->flush();


        
    }

   #[Route('/admin/recipe/create', name: 'admin.recipe.create', methods: ['GET', 'POST'])]

    public function create(Request $request , EntityManagerInterface $em){

        $recipes = new Recipe();
        $form = $this->createForm(RecipeType::class , $recipes , [
            'allow_extra_fields' => true, ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recipes->setCreatedAt(new \DateTimeImmutable());
            $recipes->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($recipes);
            $em->flush();
            $this->addFlash('success' , 'la recette a bien ete ajouter');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/create.html.twig' , [
            'form' =>$form
        ]);
    }
    // #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    // public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    // {
    //     $recipe = $repository->find($id);
    //     if ($recipe->getSlug() != $slug) {
    //         return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
    //     }
    //     return $this->render('recipe/show.html.twig', [
    //         'recipe' => $recipe



    //     ]);
    // }
    #[Route('/admin/recipe/{id}', name: 'admin.recipe.edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]

    public function edit(Recipe $recipe , Request $request , EntityManagerInterface $em){
        $form =  $this->createForm(RecipeType::class , $recipe , [
            'allow_extra_fields' => true, ]);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()){
        //     /** @var UploadedFile $file */
        //     $file = $form->get('thumbnailFile')->getData();
        //     $filename = $recipe->getId() . '.' . $file->getClientOriginalExtension();
        //     $file->move($this->getParameter('kernel.project_dir') . '/public/recettes/images', $filename);
        //     $recipe->setThumbnail($filename);
        
            // dd($file->getClientOriginalName(), $file->getClientOriginalExtension());
        
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $em ->flush();
            $this->addFlash('success' , 'La recette a bien ete modifier');

            return $this->redirectToRoute('admin.recipe.index');

        }
        return $this->render('admin/recipe/edit.html.twig' , [
            'recipe' => $recipe ,
            'form' => $form

        ]);
    }
     
  

    #[Route('/admin/recipe/delete/{id}', name: 'admin.recipe.delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]

    public function remove(Request $request,RecipeRepository $repository ,$id , Recipe $recipes, EntityManagerInterface $em): Response
    {
        $recipes = $repository->find($id);
        $em->remove($recipes);
        $em->flush();
        $this->addFlash('success' , 'La recipe a bien été supprimée');
        return $this->redirectToRoute('admin.recipe.index');
    }
    

    
}


// #[Route('/recette/{slug}-{id}', name: 'recipe.show' )]
// public function index(Request $request , string $slug , int $id): Response
// {
//    dd($slug , $id);
// }