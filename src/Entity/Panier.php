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

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=ProduitsPanier::class, mappedBy="panier", orphanRemoval=true)
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
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


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|ProduitsPanier[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(ProduitsPanier $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setPanier($this);
        }

        return $this;
    }

    public function removeProduit(ProduitsPanier $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getPanier() === $this) {
                $produit->setPanier(null);
            }
        }

        return $this;
    }
}
