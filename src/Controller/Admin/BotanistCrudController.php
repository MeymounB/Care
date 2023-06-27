<?php

namespace App\Controller\Admin;

use App\Entity\Botanist;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BotanistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Botanist::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideWhenCreating(),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
