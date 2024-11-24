<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class EmailResetPassword
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private TranslatorInterface $translator,
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private string $appName,
        private array $sender,
    ) {
    }

    public function processSending(
        string $emailFormData,
        int $resetRequestLifetime = 3600,
    ): ?ResetPasswordToken {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return null;
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user, $resetRequestLifetime);
        } catch (ResetPasswordExceptionInterface $e) {
            return null;
        }

        $email = (new TemplatedEmail())
            ->from(new Address($this->sender['email'], $this->sender['name']))
            ->to((string) $user->getEmail())
            ->subject($this->appName.' - '.$this->translator->trans('Votre lien pour choisir un nouveau mot de passe'))
            ->htmlTemplate('security/reset_password/email.html.twig')
            ->context([
                'reset_token' => $resetToken,
            ])
        ;

        $this->mailer->send($email);

        return $resetToken;
    }
}
