<?php

namespace App\Entity;

use App\Repository\UniteRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\EtatUnite;

#[ORM\Entity(repositoryClass: UniteRepository::class)]
class Unite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column(enumType: EtatUnite::class)]
    private EtatUnite $etat = EtatUnite::OK;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $couleur = null;

    #[ORM\ManyToOne(inversedBy: 'unites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Baie $baie = null;

    #[ORM\ManyToOne(inversedBy: 'unites')]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEtat(): EtatUnite
    {
        return $this->etat;
    }

    public function setEtat(EtatUnite $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getBaie(): ?Baie
    {
        return $this->baie;
    }

    public function setBaie(?Baie $baie): static
    {
        $this->baie = $baie;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
