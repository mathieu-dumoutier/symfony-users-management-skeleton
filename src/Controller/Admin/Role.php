<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Role as RoleEntity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_SUPER_ADMIN')]
class Role extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoleEntity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnIndex()
                ->hideOnForm(),
            TextField::new('name'),
            TextField::new('subject')
                ->setHelp('Permet de regrouper les permissions par thème dans la sélection des permissions sur l\'édition d\'un groupe'),
            TextField::new('key')
                ->hideOnIndex(),
            AssociationField::new('groups'),
            AssociationField::new('users'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('key')
            ->add('subject')
            ->add('groups')
        ;
    }
}
