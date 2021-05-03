<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ingredient
 *
 * @ORM\Table(name="ingredient", uniqueConstraints={@ORM\UniqueConstraint(name="ingredient_title_key", columns={"title"})}, indexes={@ORM\Index(name="ingredient_id", columns={"id"})})
 * @ORM\Entity
 */
class Ingredient
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"comment"="unique id of ingredient in table"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ingredient_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="text", nullable=true, options={"comment"="name of the ingredient"})
     */
    private $title;

    /**
     * @var json|null
     *
     * @ORM\Column(name="vitamins", type="json", nullable=true, options={"comment"="information about vitamin content in ingredients, represented in JSON format: {""Vitamins name"":""amount in milligrams""}"})
     */
    private $vitamins;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Recipe", mappedBy="ingredient")
     */
    private $recipe;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recipe = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
