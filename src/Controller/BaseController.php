<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected function getClient(): ?Client
    {
        /** @var Client $client */
        $client = $this->getUser();
        return $client;
    }
}
