<?php

namespace App\Controller;

use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OffreController extends AbstractController
{
    #[Route('/offres', name: 'app_offres')]
    public function index(OffreRepository $repo): Response
    {
        $offres = [];

        foreach ($repo->findBy(['actif' => true], ['nbUnites' => 'ASC']) as $offre) {
            $mensuel = (float) $offre->getPrixMensuel();

            $offres[] = [
                'offre' => $offre,
                'mensuel' => $mensuel,
                // Tarif annuel : 12 mois avec 10 % de remise supplémentaire
                'annuel' => round($mensuel * 12 * 0.9, 2),
                'parUnite' => round($mensuel / $offre->getNbUnites(), 2),
            ];
        }

        return $this->render('offre/index.html.twig', ['offres' => $offres]);
    }
}
