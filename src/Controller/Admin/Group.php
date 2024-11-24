<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Group as GroupEntity;
use App\Repository\GroupRepository;
use App\Repository\RoleRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminAction;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;

class Group extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GroupEntity::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $matrixAction = Action::new('matrix', 'Matrice de droits', 'fa fa-table')
            ->linkToCrudAction('matrix')
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $matrixAction);
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
            AssociationField::new('users')
                ->hideOnForm(),
            AssociationField::new('roles'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('roles')
        ;
    }

    #[AdminAction(routePath: '/matrix', routeName: 'group_matrix')]
    public function matrix(
        GroupRepository $groupRepository,
        RoleRepository $roleRepository,
    ): Response {
        return $this->render('admin/group/matrix.html.twig', [
            'group_entities' => $groupRepository->findAll(),
            'role_entities' => $roleRepository->findAll(),
        ]);
    }

    #[AdminAction(routePath: '/matrix/toggle', routeName: 'group_matrix_toggle', methods: ['POST'])]
    public function toggleRoleGroup(
        AdminContext $adminContext,
        GroupRepository $groupRepository,
        RoleRepository $roleRepository,
    ): Response {
        $group = $groupRepository->find($adminContext->getEntity()->getInstance()->getId());
        $role = $roleRepository->find($adminContext->getRequest()->toArray()['role_id']);

        if ($group->getRoles()->contains($role)) {
            $group->removeRole($role);
            $checked = false;
        } else {
            $group->addRole($role);
            $checked = true;
        }

        $groupRepository->save($group);

        return $this->json([
            'checked' => $checked,
        ]);
    }
}
