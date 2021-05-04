<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
                ->renderContentMaximized()
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield AssociationField::new('ingredients');
        yield TextEditorField::new('description');
    }

}
