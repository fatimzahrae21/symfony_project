<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function paginateRecipes(Request $request): Paginator
    {
        // Récupérer la page depuis les paramètres de la requête (avec une valeur par défaut de 1)
        $page = $request->query->getInt('page', 1);
        $limit = 2; // Nombre de résultats par page (fixe ici, ou récupérer dynamiquement si besoin)
    
        return new Paginator(
            $this->createQueryBuilder('r')
                ->setFirstResult(($page - 1) * $limit) // Calcul de l'offset
                ->setMaxResults($limit) // Nombre de résultats par page
                ->getQuery()
                ->setHint(Paginator::HINT_ENABLE_DISTINCT, false), // Option pour éviter les doublons
            false
        );
    }

    public function findTotalDuration(){
        return $this->createQueryBuilder('r')
        ->select('SUM(r.duration) as total')
        ->getQuery()
        ->getResult();
    }
 /**
    * @return Recipe[] 
   */

    public function findWithDurationLowerThan(int $duration): array
    {
        return $this-> createQueryBuilder('r')
        ->where('r.duration < :duration')
        ->orderBy('r.duration' , 'ASC')
        // ->andWhere('r.category = 2')
        ->setMaxResults(10)
        ->setParameter('duration' ,$duration)
        ->getQuery()
        ->getResult();

    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
