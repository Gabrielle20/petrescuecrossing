<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentsRepository::class)
 */
class Documents
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Dossier::class, inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dossier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cni;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $justif_dom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userResponse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(?string $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    public function getJustifDom(): ?string
    {
        return $this->justif_dom;
    }

    public function setJustifDom(?string $justif_dom): self
    {
        $this->justif_dom = $justif_dom;

        return $this;
    }

    public function getUserResponse(): ?string
    {
        return $this->userResponse;
    }

    public function setUserResponse(?string $userResponse): self
    {
        $this->userResponse = $userResponse;

        return $this;
    }
}
