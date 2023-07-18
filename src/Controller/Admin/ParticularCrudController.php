<?php

namespace App\Controller\Admin;

use App\Entity\Particular;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ParticularCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Particular::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            BooleanField::new('is_verified'),
            TextField::new('FullName')->setLabel('Full Name'),
            TextField::new('email'),
            // CollectionField::new('plants')->setLabel('Name of plants'),
            NumberField::new('plantCount')->setLabel('Number of plants'),
            TextField::new('requestTypes', 'Have created a request for:'),
            CollectionField::new('address')->setLabel('Address of the particular'),
        ];
    }
}
