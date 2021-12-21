<?php

namespace App\Entity;

use App\Repository\ProduitsPanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitsPanierRepository::class)
 */
class ProduitsPanier
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
    private $prix;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class, inversedBy="produitsPaniers")
     */
    private $produit_id;

    /**
     * @ORM\ManyToMany(targetEntity=Panier::class, inversedBy="produitsPaniers")
     */
    private $panier_id;

    public function __construct()
    {
        $this->produit_id = new ArrayCollection();
        $this->panier_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduitId(): Collection
    {
        return $this->produit_id;
    }

    public function addProduitId(Produit $produitId): self
    {
        if (!$this->produit_id->contains($produitId)) {
            $this->produit_id[] = $produitId;
        }

        return $this;
    }

    public function removeProduitId(Produit $produitId): self
    {
        $this->produit_id->removeElement($produitId);

        return $this;
    }

    /**
     * @return Collection|Panier[]
     */
    public function getPanierId(): Collection
    {
        return $this->panier_id;
    }

    public function addPanierId(Panier $panierId): self
    {
        if (!$this->panier_id->contains($panierId)) {
            $this->panier_id[] = $panierId;
        }

        return $this;
    }

    public function removePanierId(Panier $panierId): self
    {
        $this->panier_id->removeElement($panierId);

        return $this;
    }
}
