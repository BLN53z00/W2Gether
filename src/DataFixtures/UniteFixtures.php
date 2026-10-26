<?php

namespace App\DataFixtures;

use App\Entity\Baie;
use App\Entity\Unite;
use App\Enum\EtatUnite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UniteFixtures extends Fixture implements DependentFixtureInterface
{
    public const UNITE_PREFIX = 'unite_';   // ex : unite_14_6 = B014-U06

    public function load(ObjectManager $om): void
    {
        for ($b = 1; $b <= 30; $b++) {
            $baie = $this->getReference(BaieFixtures::BAIE_PREFIX . $b, Baie::class);

            for ($u = 1; $u <= 42; $u++) {
                $unite = (new Unite())
                    ->setNumero($u)
                    ->setEtat(EtatUnite::OK)
                    ->setBaie($baie);
                $om->persist($unite);
                $this->addReference(self::UNITE_PREFIX . $b . '_' . $u, $unite);
            }
        }

        $om->flush();
    }

    public function getDependencies(): array
    {
        return [BaieFixtures::class];
    }
}
