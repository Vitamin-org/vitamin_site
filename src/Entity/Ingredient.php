<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ingredient
 *
 * @ORM\Table(name="ingredient", uniqueConstraints={@ORM\UniqueConstraint(name="ingredient_title_key", columns={"title"})}, indexes={@ORM\Index(name="ingredient_id", columns={"id"})})
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"comment"="unique id of ingredient in table"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ingredient_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="text", nullable=false, options={"comment"="name of the ingredient"})
     */
    private $title;

    /**
     *  @ORM\Column(name="vitamins", type="json", nullable=true, options={"comment"="information about vitamin content in ingredients, represented in JSON format: {""Vitamins name"":""amount in milligrams""}"})
     */
    private $vitamins = [];

    /**
     * @ORM\ManyToMany(targetEntity=Recipe::class, mappedBy="ingredients")
     */
    private $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
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

    public function getVitamins(): ?array
    {
        return $this->vitamins;
    }

    public function setVitamins(array $vitamins): self
    {
        $this->vitamins = $vitamins;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->addIngredient($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeIngredient($this);
        }

        return $this;
    }
}
