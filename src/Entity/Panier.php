<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="integer")
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="paniers")
     */
    private $user_id;

    /**
     * @ORM\ManyToMany(targetEntity=ProduitsPanier::class, mappedBy="panier_id")
     */
    private $produitsPaniers;

    public function __construct()
    {
        $this->produitsPaniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection|ProduitsPanier[]
     */
    public function getProduitsPaniers(): Collection
    {
        return $this->produitsPaniers;
    }

    public function addProduitsPanier(ProduitsPanier $produitsPanier): self
    {
        if (!$this->produitsPaniers->contains($produitsPanier)) {
            $this->produitsPaniers[] = $produitsPanier;
            $produitsPanier->addPanierId($this);
        }

        return $this;
    }

    public function removeProduitsPanier(ProduitsPanier $produitsPanier): self
    {
        if ($this->produitsPaniers->removeElement($produitsPanier)) {
            $produitsPanier->removePanierId($this);
        }

        return $this;
    }
}
