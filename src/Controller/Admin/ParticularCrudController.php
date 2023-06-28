<?php

namespace App\Controller\Admin;

use App\Entity\Particular;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
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
            IdField::new('id')->hideWhenCreating(),
            TextField::new('FullName')->setLabel('Full Name'),
            TextField::new('email'),
            CollectionField::new('plants')->setLabel('Name of plants'),
            TextField::new('requestTypes', 'Types of Requests'),
            CollectionField::new('address')->setLabel('Adress of the particular'),
        ];
    }
}
