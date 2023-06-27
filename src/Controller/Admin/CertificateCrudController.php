<?php

namespace App\Controller\Admin;

use App\Entity\Certificate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CertificateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Certificate::class;
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
