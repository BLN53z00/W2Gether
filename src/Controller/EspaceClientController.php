<?php

namespace App\Controller;

use App\Entity\Baie;
use App\Entity\Unite;
use App\Form\UniteType;
use App\Repository\UniteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mon-espace')]
#[IsGranted('ROLE_CLIENT')]
class EspaceClientController extends AbstractController
{
    // Tableau de bord
    #[Route('', name: 'espace_accueil')]
    public function index(UniteRepository $repo): Response
    {
        $unites = $repo->findByClient($this->getUser());

        $baies = [];
        $incidents = 0;
        foreach ($unites as $u) {
            $baies[$u->getBaie()->getId()] = true;
            if ($u->getEtat()->value !== 'ok') {
                $incidents++;
            }
        }

        return $this->render('espace/index.html.twig', [
            'nbUnites' => count($unites),
            'nbBaies' => count($baies),
            'nbIncidents' => $incidents,
        ]);
    }

    // Liste de mes unités
    #[Route('/unites', name: 'espace_unites')]
    public function unites(UniteRepository $repo): Response
    {
        return $this->render('espace/unites.html.twig', [
            'unites' => $repo->findByClient($this->getUser()),
        ]);
    }

    // Modifier une unité (nom, type, couleur)
    #[Route('/unites/{id}/modifier', name: 'espace_unite_edit')]
    public function edit(Unite $unite, Request $request, EntityManagerInterface $em): Response
    {
        // Un client ne peut modifier que SES unités
        if ($unite->getCommande()?->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(UniteType::class, $unite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Unité mise à jour.');
            return $this->redirectToRoute('espace_unites');
        }

        return $this->render('espace/unite_edit.html.twig', [
            'form' => $form,
            'unite' => $unite,
        ]);
    }

    // Mes baies
    #[Route('/baies', name: 'espace_baies')]
    public function baies(UniteRepository $repo): Response
    {
        $mesUnites = $repo->findByClient($this->getUser());

        // Regroupe par baie : [id baie => ['baie' => Baie, 'unites' => [numero => Unite]]]
        $baies = [];
        foreach ($mesUnites as $u) {
            $id = $u->getBaie()->getId();
            $baies[$id]['baie'] ??= $u->getBaie();
            $baies[$id]['unites'][$u->getNumero()] = $u;
        }

        return $this->render('espace/baies.html.twig', ['baies' => $baies]);
    }
}
