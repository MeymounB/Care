<?php

namespace App\Controller\Admin;

use App\Entity\Plant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class PlantCrudController extends AbstractCrudController
{

    use Trait\ReadOnlyTrait;
    public static function getEntityFqcn(): string
    {
        return Plant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            TextField::new('species'),
            TextField::new('description'),
            ImageField::new('smallThumbnail'),
            ImageField::new('thumbnail'),
            DateTimeField::new('createdAt'),
            // CollectionField::new('particular'),
            TextField::new('particularName', 'Nom du propriétaire'),

            CollectionField::new('comments')->onlyOnDetail(),
        ];
    }
}
