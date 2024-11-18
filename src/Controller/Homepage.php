<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_homepage')]
class Homepage extends AbstractController
{
    #[Template('homepage.html.twig')]
    public function __invoke(UserRepository $userRepository): array
    {
        return [
            'first_launch' => $userRepository->countAll() === 0,
        ];
    }
}
