<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recipe
 *
 * @ORM\Table(name="recipe", uniqueConstraints={@ORM\UniqueConstraint(name="recipe_title_key", columns={"title"})}, indexes={@ORM\Index(name="recipe_idx", columns={"id"})})
 * @ORM\Entity
 */
class Recipe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"comment"="unique id of recipe in table"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="recipe_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="text", nullable=true, options={"comment"="title of the recipe"})
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true, options={"comment"="some description about recipe: tutorial how to cook"})
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Ingredient", inversedBy="recipe")
     * @ORM\JoinTable(name="recipe_ingredient",
     *   joinColumns={
     *     @ORM\JoinColumn(name="recipe_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ingredient_id", referencedColumnName="id")
     *   }
     * )
     */
    private $ingredient;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ingredient = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
