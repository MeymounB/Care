<?php

namespace App\Controller\Admin;

use App\Entity\Particular;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ParticularCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Particular::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
