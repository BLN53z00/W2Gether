<?php

namespace App\DataFixtures;

use App\Entity\Offre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OffreFixtures extends Fixture
{
    public const OFFRE_PREFIX = 'offre_';

    public function load(ObjectManager $om): void
    {
        $offres = [
            'base'       => ['Base', 1, '100.00', 0],
            'startup'    => ['Start-up', 10, '900.00', 10],
            'pme'        => ['PME', 21, '1680.00', 20],
            'entreprise' => ['Entreprise', 42, '2940.00', 30],
        ];

        foreach ($offres as $cle => [$nom, $nb, $prix, $remise]) {
            $o = (new Offre())
                ->setNom($nom)->setNbUnites($nb)->setPrixMensuel($prix)
                ->setRemisePourcent($remise)->setActif(true);
            $om->persist($o);
            $this->addReference(self::OFFRE_PREFIX . $cle, $o);
        }

        $om->flush();
    }
}
