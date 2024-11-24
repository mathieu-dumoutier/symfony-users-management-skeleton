<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Configuration;
use App\Entity\Group;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard as EasyAdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Dashboard extends AbstractDashboardController
{
    public function __construct(
        private UserRepository $userRepository,
        private string $appName = '',
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'enabledUsers' => $this->userRepository->countEnabled(),
        ]);
    }

    public function configureDashboard(): EasyAdminDashboard
    {
        return EasyAdminDashboard::new()
            ->setTitle($this->appName);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Users', 'far fa-user', User::class);
        yield MenuItem::linkToCrud('Groups', 'fas fa-users', Group::class);
        yield MenuItem::section('Technical settings')
            ->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Configurations', 'fas fa-wrench', Configuration::class)
            ->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Roles', 'far fa-user', Role::class)
            ->setPermission('ROLE_SUPER_ADMIN');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('https://rsms.me/inter/inter.css')
            ->addWebpackEncoreEntry('app')
        ;
    }
}
