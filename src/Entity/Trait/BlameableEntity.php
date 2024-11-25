<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait BlameableEntity
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Gedmo\Blameable]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Gedmo\Blameable]
    private ?User $updatedBy = null;
}
