<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/register', name: 'app_register', condition: '1 == %registration_enabled%')]
class Register extends AbstractController
{
    #[Template('security/register.html.twig')]
    public function __invoke(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository $userRepository,
        EmailVerifier $emailVerifier,
        TranslatorInterface $translator,
        array $sender,
    ): array|RedirectResponse {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // if it's the first user, make it a super admin
            if (0 === $userRepository->countAll()) {
                $user->setRoles(['ROLE_SUPER_ADMIN']);
            }

            $userRepository->save($user);

            try {
                // generate a signed url and email it to the user
                $emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address($sender['email'], $sender['name']))
                        ->to((string) $user->getEmail())
                        ->subject($translator->trans('Confirmez votre adresse e-mail pour activer votre compte'))
                        ->htmlTemplate('security/confirmation_email.html.twig')
                );

                $this->addFlash('success', $translator->trans('Vérifiez votre boîte mail pour activer votre compte.'));
            } catch (TransportException $exception) {
                $this->addFlash('warning', $translator->trans('Un problème est survenu lors de l\'envoi du mail pour activer votre compte. Détail : ').$exception->getMessage());
            }

            return $this->redirectToRoute('app_register');
        }

        return [
            'registration_form' => $form,
        ];
    }
}
