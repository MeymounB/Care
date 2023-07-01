<?php

namespace App\Controller\Admin;

use App\Entity\Status;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class StatusCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Status::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DETAIL);
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
