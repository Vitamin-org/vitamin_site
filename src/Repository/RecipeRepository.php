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

    private $mainQuery = ' SELECT r.id
FROM recipe AS r
         JOIN recipe_ingredient ri ON r.id = ri.recipe_id
         JOIN ingredient i on i.id = ri.ingredient_id
         ';

    private $includeVitaminsQuery = ' EXISTS(
        SELECT
        FROM ingredient include_i2
                 JOIN recipe_ingredient ri3 ON include_i2.id = ri3.ingredient_id
        WHERE ri3.recipe_id = r.id
          AND jsonb_exists_any(include_i2.vitamins::jsonb, :includeVitamins)
    )';

    private $excludeVitaminsQuery = ' NOT EXISTS(
        SELECT
        FROM ingredient include_i2
                 JOIN recipe_ingredient ri3 ON include_i2.id = ri3.ingredient_id
        WHERE ri3.recipe_id = r.id
          AND jsonb_exists_any(include_i2.vitamins::jsonb, :excludeVitamins)
    )';

    private $includeIngredientsQuery = ' EXISTS(
        SELECT
        FROM ingredient include_i
                 JOIN recipe_ingredient ri2 ON include_i.id = ri2.ingredient_id
        WHERE ri2.recipe_id = r.id
          AND include_i.title = ANY (:includeIngredients)
    )';

    private $excludeIngredientsQuery = ' NOT EXISTS(
        SELECT
        FROM ingredient include_i
                 JOIN recipe_ingredient ri2 ON include_i.id = ri2.ingredient_id
        WHERE ri2.recipe_id = r.id
          AND include_i.title = ANY (:excludeIngredients)
    )';

    private $endingOfQuery = ' GROUP BY r.id;';
    private $where = ' WHERE ';
    private $and = ' AND ';

    /**
     * Finds all recipes matching to the filters. Parameters: arrays of filters - includeVitamins, includeIngredients, excludeVitamins, excludeIngridients
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function findAllByFilters(array $includeIngredients = [], array $includeVitamins = [], array $excludeIngredients = [], array $excludeVitamins = []): array
    {
        $inclVitCount = count($includeVitamins);
        $inclIngCount = count($includeIngredients);
        $exclVitCount = count($excludeVitamins);
        $exclIngCount = count($excludeIngredients);

        $conn = $this->getEntityManager()->getConnection();

        if ($inclIngCount == 0 && $inclVitCount == 0 && $exclIngCount == 0 && $exclVitCount == 0) {             // нет никаких данных, выведется всё
            $sql = 'SELECT r.id FROM recipe AS r;';
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery();
        }

        else if ($inclIngCount != 0 && $inclVitCount == 0 && $exclIngCount == 0 && $exclVitCount == 0) {
            $sql = $this->mainQuery . $this->where . $this->includeIngredientsQuery . $this->endingOfQuery;
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['includeIngredients' => $this->getRightStringRepresentation($includeIngredients)]);

        } else if ($inclIngCount == 0 && $inclVitCount != 0 && $exclIngCount == 0 && $exclVitCount == 0) {
            $sql = $this->mainQuery . $this->where . $this->includeVitaminsQuery . $this->endingOfQuery;
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['includeVitamins' => $this->getRightStringRepresentation($includeVitamins)]);

        } else if ($inclIngCount == 0 && $inclVitCount == 0 && $exclIngCount != 0 && $exclVitCount == 0) {
            $sql = $this->mainQuery . $this->where . $this->excludeIngredientsQuery . $this->endingOfQuery;
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['excludeIngredients' => $this->getRightStringRepresentation($excludeIngredients)]);

        } else if ($inclIngCount == 0 && $inclVitCount == 0 && $exclIngCount == 0 && $exclVitCount != 0) {
            $sql = $this->mainQuery . $this->where . $this->excludeVitaminsQuery . $this->endingOfQuery;
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['excludeVitamins' => $this->getRightStringRepresentation($excludeVitamins)]);
        }

        else if ($inclIngCount == 0 && $inclVitCount == 0 && $exclIngCount == 0) {                      // нет includeIngredients, includeVitamins и excludeIngredients
            $sql = $this->mainQuery . $this->where . $this->excludeVitaminsQuery . $this->endingOfQuery;
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['excludeVitamins' => $this->getRightStringRepresentation($excludeVitamins)]);
        }

        else if ($inclIngCount == 0 && $inclVitCount == 0) {                                            // нет includeIngredients и includeVitamins
            $sql = $this->mainQuery . $this->where . $this->excludeIngredientsQuery . $this->and . $this->excludeVitaminsQuery . $this->endingOfQuery;
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['excludeIngredients' => $this->getRightStringRepresentation($excludeIngredients),
                'excludeVitamins' => $this->getRightStringRepresentation($excludeVitamins)]);
        }

        else if ($inclIngCount == 0) {                                                                  // нет только includeIngredients
            $sql = $this->mainQuery . $this->where . $this->includeVitaminsQuery . $this->and . $this->excludeIngredientsQuery . $this->and . $this->excludeVitaminsQuery . $this->endingOfQuery;
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['includeVitamins' => $this->getRightStringRepresentation($includeVitamins),
                'excludeIngredients' => $this->getRightStringRepresentation($excludeIngredients),
                'excludeVitamins' => $this->getRightStringRepresentation($excludeVitamins)]);
        }

        else  {  // есть все параметры поиска ($inclIngCount != 0 && $inclVitCount != 0 && $exclIngCount != 0 && $exclVitCount != 0 )
            $sql = $this->mainQuery . $this->where . $this->includeIngredientsQuery . $this->and . $this->includeVitaminsQuery . $this->and . $this->excludeIngredientsQuery . $this->and . $this->excludeVitaminsQuery . $this->endingOfQuery;
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['includeIngredients' => $this->getRightStringRepresentation($includeIngredients),
                'includeVitamins' => $this->getRightStringRepresentation($includeVitamins),
                'excludeIngredients' => $this->getRightStringRepresentation($excludeIngredients),
                'excludeVitamins' => $this->getRightStringRepresentation($excludeVitamins)]);
        }


        $recipesIDS = $stmt->fetchAllAssociative();

        $actualRecipes = array();
        foreach ($recipesIDS as $id) {
            array_push($actualRecipes, $this->find($id));
        }

        return $actualRecipes;
    }

    private function getRightStringRepresentation(array $array): string
    {
        $string = implode("\", \"", $array);

        return "{\"" . $string . "\"}";
    }

}
