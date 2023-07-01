<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Trait\ReadDeleteTrait;
use App\Entity\Plant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlantCrudController extends AbstractCrudController
{
    use ReadDeleteTrait;

    public static function getEntityFqcn(): string
    {
        return Plant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            TextField::new('name'),
            TextField::new('species'),
            TextField::new('description'),
            DateTimeField::new('createdAt'),
            AssociationField::new('particular', 'Nom du propriétaire'),
            ImageField::new('thumbnails'),
        ];
    }
}
