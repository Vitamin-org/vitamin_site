<?php

namespace App\Controller\Admin;

use App\Entity\Template;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TemplateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Template::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
<<<<<<< HEAD
        yield CollectionField::new('include_ingredients');
        yield CollectionField::new('include_vitamins');
        yield CollectionField::new('exclude_ingredients');
        yield CollectionField::new('exclude_vitamins');
=======
        yield TextareaField::new('filters');
>>>>>>> main
    }
}
