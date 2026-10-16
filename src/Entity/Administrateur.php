<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Administrateur extends Utilisateur
{
    public function getRoleMetier(): string
    {
        return 'ROLE_ADMIN';
    }
}
