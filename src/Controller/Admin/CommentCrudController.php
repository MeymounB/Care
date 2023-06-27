<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Controller\Admin\Trait\ReadDeleteTrait;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class CommentCrudController extends AbstractCrudController
{
    use ReadDeleteTrait;
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideWhenCreating(),
            TextField::new('content'),
            DateTimeField::new('createdAt'),
            AssociationField::new('user')
                ->hideWhenCreating(),
            // ArrayField::new('roles'),

        ];
    }
}
