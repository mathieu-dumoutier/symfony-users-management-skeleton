<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Configuration as ConfigurationEntity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class Configuration extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ConfigurationEntity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['key' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $keyField = TextField::new('key')
            ->setTemplatePath('admin/configuration/key.html.twig');

        if (Crud::PAGE_EDIT === $pageName) {
            $keyField->setDisabled();
        }

        return [
            TextareaField::new('description')
                ->hideOnIndex(),
            $keyField,
            TextField::new('value'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('key')
            ->add('value')
        ;
    }
}
