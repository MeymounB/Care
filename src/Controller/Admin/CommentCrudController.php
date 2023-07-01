<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Trait\ReadDeleteTrait;
use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            IdField::new('id')
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            TextField::new('user.fullName', 'User Name')->onlyOnIndex(),
            TextField::new('content'),
            TextField::new('adviceTitle', 'Advice Title')->onlyOnIndex(),
            TextField::new('userRole', 'User Role')->onlyOnIndex(),
            DateTimeField::new('createdAt'),
        ];
    }
}
