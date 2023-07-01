<?php

namespace App\Controller\Admin;

use App\Entity\Certificate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CertificateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Certificate::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            TextField::new('title'),
            TextField::new('state'),
            TextField::new('certificateFile'),
            AssociationField::new('botanist'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt'),
        ];
    }
}
