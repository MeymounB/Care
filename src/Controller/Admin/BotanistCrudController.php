<?php

namespace App\Controller\Admin;

use App\Entity\Botanist;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BotanistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Botanist::class;
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
            // TextField::new('request', 'Have taken request'),
        ];
    }
}
