<?php

namespace App\DataFixtures;

use App\Entity\Administrateur;
use App\Entity\Client;
use App\Entity\Comptable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{
    public const CLIENT_PREFIX = 'client_';

    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $om): void
    {
        $admin = (new Administrateur())
            ->setNom('Admin')->setPrenom('Root')->setEmail('admin@worktogether.test');
        $admin->setPassword($this->hasher->hashPassword($admin, 'Admin123!'));
        $om->persist($admin);

        $compta = (new Comptable())
            ->setNom('Compta')->setPrenom('Alice')->setEmail('compta@worktogether.test');
        $compta->setPassword($this->hasher->hashPassword($compta, 'Compta123!'));
        $om->persist($compta);

        $clients = [
            ['Durand', 'Paul', 'client@worktogether.test', 'Durand SARL'],
            ['Martin', 'Sophie', 'sophie@worktogether.test', 'Martin Conseil'],
            ['Bernard', 'Luc', 'luc@worktogether.test', 'StartLab'],
        ];
        foreach ($clients as $i => [$nom, $prenom, $email, $entreprise]) {
            $c = (new Client())
                ->setNom($nom)->setPrenom($prenom)->setEmail($email)
                ->setEntreprise($entreprise)->setTelephone('060000000' . $i);
            $c->setPassword($this->hasher->hashPassword($c, 'Client123!'));
            $om->persist($c);
            $this->addReference(self::CLIENT_PREFIX . $i, $c);
        }

        $om->flush();
    }
}
