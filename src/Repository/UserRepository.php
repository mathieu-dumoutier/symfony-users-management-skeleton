<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

use function Symfony\Component\Clock\now;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    use SaveAndRemoveMethodTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countEnabled(): int
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.isVerified = true')
            ->andWhere('u.disabledAt IS NULL OR u.disabledAt > :now')
            ->setParameter('now', now())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getEnabled(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.isVerified = true')
            ->andWhere('u.disabledAt IS NULL OR u.disabledAt > :now')
            ->setParameter('now', now())
            ->getQuery()
            ->getResult();
    }
}
