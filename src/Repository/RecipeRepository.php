<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    private function getRightStringRepresentation(array $array): string
    {
        $string = "{";
        foreach ($array as &$value) {
            $string .= "\"" . $value . "\", ";
        }
        $string_length = strlen($string);

        $string = substr($string, 0, $string_length - 2);
        $string .= "}";

        return $string;

    }

    /**
     * Finds all recipes matching to the filters. Parameters: arrays of filters - includeVitamins, includeIngredients, excludeVitamins, excludeIngridients
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function findAllByFilters(array $includeVitamins = [], array $includeIngredients = [], array $excludeVitamins = [], array $excludeIngredients = []): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
FROM recipe AS r
         JOIN recipe_ingredient ri ON r.id = ri.recipe_id
         JOIN ingredient i on i.id = ri.ingredient_id
WHERE EXISTS(
        SELECT
        FROM ingredient include_i
                 JOIN recipe_ingredient ri2 ON include_i.id = ri2.ingredient_id
        WHERE ri2.recipe_id = r.id
          AND include_i.title = ANY (:includeIngredients)
    )
  AND EXISTS(
        SELECT
        FROM ingredient include_i2
                 JOIN recipe_ingredient ri3 ON include_i2.id = ri3.ingredient_id
        WHERE ri3.recipe_id = r.id
          AND include_i2.vitamins::jsonb ?| :includeVitamins
    )
  AND NOT EXISTS(
        SELECT
        FROM ingredient include_i
                 JOIN recipe_ingredient ri2 ON include_i.id = ri2.ingredient_id
        WHERE ri2.recipe_id = r.id
          AND include_i.title = ANY (:excludeIngredients)
    )
  AND NOT EXISTS(
        SELECT
        FROM ingredient include_i2
                 JOIN recipe_ingredient ri3 ON include_i2.id = ri3.ingredient_id
        WHERE ri3.recipe_id = r.id
          AND include_i2.vitamins::jsonb ?| :excludeVitamins
    )
;';

        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['includeIngredients' => $this->getRightStringRepresentation($includeIngredients),
            'includeVitamins' => $this->getRightStringRepresentation("$includeVitamins"),
            'excludeIngredients' => $this->getRightStringRepresentation($excludeIngredients),
            'excludeVitamins' => $this->getRightStringRepresentation($excludeVitamins)]);

        return $stmt->fetchAllAssociative();
    }

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
