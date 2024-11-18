<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private string $senderEmail;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Ensure we have a clean database
        $container = static::getContainer();
        $this->senderEmail = $container->getParameter('sender')['email'];

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();
        $this->em = $em;

        $this->userRepository = $container->get(UserRepository::class);

        foreach ($this->userRepository->findAll() as $user) {
            $this->em->remove($user);
        }

        $this->em->flush();
    }

    public function testResetPasswordController(): void
    {
        // Create a test user
        $user = (new User())
            ->setEmail('me@example.com')
            ->setPassword('a-test-password-that-will-be-changed-later')
        ;
        $this->em->persist($user);
        $this->em->flush();

        // Test Request reset password page
        $this->client->request('GET', '/reset-password');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Mot de passe oublié ?');

        // Submit the reset password form and test email message is queued / sent
        $this->client->submitForm('Envoyer un e-mail de réinitialisation du mot de passe', [
            'reset_password_request_form[email]' => 'me@example.com',
        ]);

        // Ensure the reset password email was sent
        self::assertEmailCount(1);

        $messages = $this->getMailerMessages();

        self::assertEmailAddressContains($messages[0], 'from', $this->senderEmail);
        self::assertEmailAddressContains($messages[0], 'to', 'me@example.com');
        self::assertEmailTextBodyContains($messages[0], 'Ce lien expirera dans 1 heure.');

        self::assertResponseRedirects('/reset-password/check-email');

        // Test check email landing page shows correct "expires at" time
        $crawler = $this->client->followRedirect();

        self::assertPageTitleContains('Email de réinitialisation du mot de passé envoyé');
        self::assertStringContainsString('Ce lien expirera dans 1 heure', $crawler->html());

        // Test the link sent in the email is valid
        $email = $messages[0]->toString();
        preg_match('#(/reset-password/reset/[a-zA-Z0-9]+)#', $email, $resetLink);

        $this->client->request('GET', $resetLink[1]);

        self::assertResponseRedirects('/reset-password/reset');

        $this->client->followRedirect();

        // Test we can set a new password
        // TODO : wait green CI on https://github.com/SymfonyCasts/reset-password-bundle
        //        $this->client->submitForm('Enregistrer le mot de passe', [
        //            'change_password_form[plainPassword][first]' => 'newStrongPassword',
        //            'change_password_form[plainPassword][second]' => 'newStrongPassword',
        //        ]);
        //
        //        self::assertResponseRedirects('/');
        //
        //        $user = $this->userRepository->findOneBy(['email' => 'me@example.com']);
        //
        //        self::assertInstanceOf(User::class, $user);
        //
        //        /** @var UserPasswordHasherInterface $passwordHasher */
        //        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        //        self::assertTrue($passwordHasher->isPasswordValid($user, 'newStrongPassword'));
    }
}
