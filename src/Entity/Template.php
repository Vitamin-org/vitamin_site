<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Template
 *
 * @ORM\Table(name="template", uniqueConstraints={@ORM\UniqueConstraint(name="template_title_key", columns={"title"})})
 * @ORM\Entity
 */
class Template
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"comment"="unique id of template in the table"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="template_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="text", nullable=true, options={"comment"="title of the template"})
     */
    private $title;

    /**
     * @var json|null
     *
     * @ORM\Column(name="filters", type="json", nullable=true, options={"comment"="filters that define this template, represented in JSON format"})
     */
    private $filters;


}
