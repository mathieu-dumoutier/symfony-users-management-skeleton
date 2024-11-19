<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User as UserEntity;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class User extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserEntity::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnIndex()
                ->hideOnForm(),
            EmailField::new('email'),
            BooleanField::new('isVerified'),
            DateTimeField::new('disabledAt'),
        ];
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
