<?php

namespace App\Entity;

use App\Repository\TemplateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Template
 *
 * @ORM\Table(name="template", uniqueConstraints={@ORM\UniqueConstraint(name="template_title_key", columns={"title"})})
 * @ORM\Entity(repositoryClass=TemplateRepository::class)
 */
class Template
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="template_id_seq", allocationSize=1, initialValue=1)
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"comment"="unique id of template in the table"})
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false, options={"comment"="title of the template"})
     */
    private $title;


    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $include_ingredients = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $include_vitamins = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $exclude_ingredients = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $exclude_vitamins = [];

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

    public function getIncludeIngredients(): ?array
    {
        return $this->include_ingredients;
    }

    public function setIncludeIngredients(?array $include_ingredients): self
    {
        $this->include_ingredients = $include_ingredients;

        return $this;
    }

    public function getIncludeVitamins(): ?array
    {
        return $this->include_vitamins;
    }

    public function setIncludeVitamins(?array $include_vitamins): self
    {
        $this->include_vitamins = $include_vitamins;

        return $this;
    }

    public function getExcludeIngredients(): ?array
    {
        return $this->exclude_ingredients;
    }

    public function setExcludeIngredients(?array $exclude_ingredients): self
    {
        $this->exclude_ingredients = $exclude_ingredients;

        return $this;
    }

    public function getExcludeVitamins(): ?array
    {
        return $this->exclude_vitamins;
    }

    public function setExcludeVitamins(?array $exclude_vitamins): self
    {
        $this->exclude_vitamins = $exclude_vitamins;

        return $this;
    }
}
