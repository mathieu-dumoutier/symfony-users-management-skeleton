<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User as UserEntity;
use App\Repository\UserRepository;
use App\Security\EmailResetPassword;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class User extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserEntity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['email' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnIndex()
                ->hideOnForm(),
            EmailField::new('email'),
            CollectionField::new('groups')
                ->hideOnForm(),
            AssociationField::new('groups')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->hideOnIndex(),
            BooleanField::new('isVerified'),
            DateTimeField::new('disabledAt')
                ->hideWhenCreating(),
            BooleanField::new('sendResetPasswordEmail')
                ->setFormTypeOption('mapped', false)
                ->onlyWhenCreating(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('email')
            ->add('isVerified')
            ->add('disabledAt')
            ->add('groups')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $sendResetPasswordEmailAction = Action::new('sendResetPasswordEmail', 'Envoyer un e-mail pour choisir un mot de passe')
            ->linkToCrudAction('sendResetPasswordEmail')
            ->displayIf(fn ($entity) => null === $entity->getDisabledAt());

        return $actions
            ->add(Crud::PAGE_EDIT, $sendResetPasswordEmailAction);
    }

    public function sendResetPasswordEmail(
        AdminContext $adminContext,
        EmailResetPassword $emailResetPassword,
        int $choosePasswordLifetime,
    ): RedirectResponse {
        $user = $adminContext->getEntity()->getInstance();

        try {
            $emailResetPassword->processSending($user->getEmail(), $choosePasswordLifetime);

            $this->addFlash(
                'success',
                'Un email a été envoyé à l\'utilisateur pour qu\'il puisse choisir un mot de passe',
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'warning',
                'Une erreur est survenue lors de l\'envoi de l\'email',
            );
        }

        return $this->redirectToRoute('admin_user_index');
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function getAll(UserRepository $userRepository): JsonResponse
    {
        foreach ($userRepository->getEnabled() as $user) {
            $users[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ];
        }

        return $this->json($users);
    }
}
