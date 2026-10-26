<?php

namespace App\DataFixtures;

use App\Entity\Baie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BaieFixtures extends Fixture
{
    public const BAIE_PREFIX = 'baie_';

    public function load(ObjectManager $om): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $baie = (new Baie())->setNumero(sprintf('B%03d', $i))->setNbUnites(42);
            $om->persist($baie);
            $this->addReference(self::BAIE_PREFIX . $i, $baie);
        }

        $om->flush();
    }
}
