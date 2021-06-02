<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Ingredient;
use Doctrine\ORM\Mapping as ORM;

/**
 * Recipe
 *
 * @ORM\Table(name="recipe", uniqueConstraints={@ORM\UniqueConstraint(name="recipe_title_key", columns={"title"})}, indexes={@ORM\Index(name="recipe_idx", columns={"id"})})
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="recipe_id_seq", allocationSize=1, initialValue=1)
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"comment"="unique id of recipe in table"})
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="text", nullable=false, options={"comment"="title of the recipe"})
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true, options={"comment"="some description about recipe: tutorial how to cook"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Ingredient::class, inversedBy="recipies")
     */
    private $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getVitamins(): ?array
    {
        $vitamins = array();
        foreach ($this->getIngredients() as $ingredient) {
            foreach($ingredient->getVitamins() as $vitamin) {
                if (!in_array($vitamin, $vitamins)) {
                    array_push($vitamins, $vitamin);
                }
            }
        }
        return $vitamins;
    }
}
