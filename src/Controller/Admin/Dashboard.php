<?php

declare(strict_types=1);

namespace App\Controller\Admin;

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
        return EasyAdminDashboard::new();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Users', 'far fa-user', User::class);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('https://rsms.me/inter/inter.css')
            ->addWebpackEncoreEntry('app')
        ;
    }
}
