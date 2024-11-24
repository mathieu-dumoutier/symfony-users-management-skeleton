<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Configuration;
use App\Entity\Group;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const string ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const string ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';
    public const string ROLE_ADMIN = 'ROLE_ADMIN';
    public const string ROLE_USER = 'ROLE_USER';

    public function load(ObjectManager $manager): void
    {
        $configuration = [
            'APP_NAME' => 'Symfony Users Management Skeleton',
            'MAILER_DSN' => 'smtp://mailer:1025',
            'SENDER_EMAIL' => 'mailer@your-domain.com',
            'SENDER_NAME' => 'Acme Mail Bot',
            'REGISTRATION_ENABLED' => '1',
            'RESET_PASSWORD_REQUEST_LIFETIME' => '3600',
            'CHOOSE_PASSWORD_LIFETIME' => '604800',
        ];

        foreach ($configuration as $key => $value) {
            $config = (new Configuration())
                ->setKey($key)
                ->setValue($value);

            $manager->persist($config);
        }

        $roles = [
            self::ROLE_SUPER_ADMIN => 'Full privileges',
            self::ROLE_ALLOWED_TO_SWITCH => 'Allowed to impersonate',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_USER => 'User',
        ];

        foreach ($roles as $key => $name) {
            $role = (new Role())
                ->setKey($key)
                ->setName($name);

            $this->addReference($key, $role);

            $manager->persist($role);
        }

        $groups = [
            'Super administrators' => [
                self::ROLE_SUPER_ADMIN,
                self::ROLE_ALLOWED_TO_SWITCH,
                self::ROLE_ADMIN,
                self::ROLE_USER,
            ],
            'Administrators' => [self::ROLE_ADMIN, self::ROLE_USER],
            'Users' => [self::ROLE_USER],
        ];

        foreach ($groups as $name => $roles) {
            $group = (new Group())
                ->setName($name);

            foreach ($roles as $role) {
                $group->addRole($this->getReference($role));
            }

            $manager->persist($group);
        }

        $manager->flush();
    }
}
