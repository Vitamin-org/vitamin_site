<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

     /**
      * @return array Returns an array of string of all vitamins in database
      */
    public function findAllVitamins()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT DISTINCT json_object_keys(ingredient.vitamins) AS KEYS
  FROM ingredient;';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();

        $vitamins = $stmt->fetchAllAssociative();

        $actualVitamins = array();
        foreach ($vitamins as $vitamin) {
            array_push($actualVitamins, $vitamin["keys"]);
        }

        return $actualVitamins;
    }
}
