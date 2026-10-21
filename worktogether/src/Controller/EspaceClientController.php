<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mon-espace')]
#[IsGranted('ROLE_CLIENT')]
class EspaceClientController extends AbstractController
{
    #[Route('', name: 'espace_accueil')]
    public function index(): Response
    {
        return $this->render('espace/index.html.twig');
    }
}
