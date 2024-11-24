<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Security\EmailResetPassword;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class EasyAdminUserPersisted implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private EmailResetPassword $emailResetPassword,
        private int $choosePasswordLifetime,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => ['sendResetPasswordEmail'],
        ];
    }

    public function sendResetPasswordEmail(AfterEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        if ($this->requestStack->getMainRequest()->request->all('User')['sendResetPasswordEmail']) {
            try {
                $this->emailResetPassword->processSending($entity->getEmail(), $this->choosePasswordLifetime);

                $this->requestStack->getSession()->getFlashBag()
                    ->add(
                        'success',
                        'Un email a été envoyé à l\'utilisateur pour qu\'il puisse choisir un mot de passe',
                    );
            } catch (\Exception $e) {
                $this->requestStack->getSession()->getFlashBag()
                    ->add(
                        'warning',
                        'Une erreur est survenue lors de l\'envoi de l\'email',
                    );
            }
        }
    }
}
