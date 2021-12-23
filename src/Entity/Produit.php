<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

        /**
     * @ORM\Column(type="text")
     */
    private $categorie;

    /**
     * @ORM\ManyToMany(targetEntity=ProduitsPanier::class, mappedBy="produit_id")
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $produitsPanier->addProduitId($this);
        }

        return $this;
    }

    public function removeProduitsPanier(ProduitsPanier $produitsPanier): self
    {
        if ($this->produitsPaniers->removeElement($produitsPanier)) {
            $produitsPanier->removeProduitId($this);
        }

        return $this;
    }
}
