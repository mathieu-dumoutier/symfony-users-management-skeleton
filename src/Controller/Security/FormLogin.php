<?php

namespace App\Controller\Security;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/login', name: 'app_login')]
class FormLogin extends AbstractController
{
    #[Template('security/form_login.html.twig')]
    public function __invoke(AuthenticationUtils $authenticationUtils): array
    {
        return [
            'last_username' =>  $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ];
    }
}
