<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Offre;
use App\Entity\Unite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommandeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $om): void
    {
        $couleurs = ['Serveur web' => '#3b82f6', 'Base de données' => '#22c55e', 'Stockage' => '#9ca3af'];
        $types = array_keys($couleurs);

        // [index client, offre, n° baie, première unité, périodicité, prix]
        $commandes = [
            [0, 'startup', 14, 1, 'mensuel', '900.00'],
            [1, 'base',    3,  5, 'mensuel', '100.00'],
            [2, 'pme',     7,  1, 'annuel',  '18144.00'],   // 1680 × 12 × 0,90
        ];

        foreach ($commandes as [$iClient, $cleOffre, $numBaie, $debut, $periodicite, $prix]) {
            $client = $this->getReference(UtilisateurFixtures::CLIENT_PREFIX . $iClient, Client::class);
            $offre  = $this->getReference(OffreFixtures::OFFRE_PREFIX . $cleOffre, Offre::class);

            $commande = (new Commande())
                ->setClient($client)
                ->setOffre($offre)
                ->setDateCreation(new \DateTimeImmutable())
                ->setDateDebut(new \DateTimeImmutable())
                ->setDateFin(new \DateTimeImmutable($periodicite === 'annuel' ? '+1 year' : '+1 month'))
                ->setPeriodicite($periodicite)
                ->setPrixTotal($prix);
            $om->persist($commande);

            // Bloc d'unités contiguës dans la même baie
            for ($k = 0; $k < $offre->getNbUnites(); $k++) {
                $unite = $this->getReference(
                    UniteFixtures::UNITE_PREFIX . $numBaie . '_' . ($debut + $k),
                    Unite::class
                );
                $type = $types[$k % 3];
                $unite->setCommande($commande)
                    ->setNom('Unité ' . ($k + 1))
                    ->setType($type)
                    ->setCouleur($couleurs[$type]);
            }
        }

        $om->flush();
    }

    public function getDependencies(): array
    {
        return [UtilisateurFixtures::class, OffreFixtures::class, UniteFixtures::class];
    }
}
