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
     * @ORM\Column(name="title", type="text", nullable=false, options={"comment"="title of the template"})
     */
    private $title;

    /**
     * @ORM\Column(name="filters", type="json", nullable=true, options={"comment"="filters that define this template, represented in JSON format"})
     */
    private $filters = [];

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

    public function getFilters(): ?array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }
}
