<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Comptable extends Utilisateur
{
    public function getRoleMetier(): string
    {
        return 'ROLE_COMPTABLE';
    }
}
