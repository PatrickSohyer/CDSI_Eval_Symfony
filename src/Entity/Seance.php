<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SeanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *              collectionOperations={"get", "post"},
 *              itemOperations={"get", "patch"},
 *              normalizationContext={"groups"={"seance:read"}},
 *              denormalizationContext={"groups"={"seance:write"}}
 * )
 * @ORM\Entity(repositoryClass=SeanceRepository::class)
 */
class Seance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"module:read", "seance:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"module:read", "seance:read"})
     */
    private $dateSeance;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Groups({"module:read", "seance:read"})
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"module:read", "seance:read", "seance:write"})
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Groups({"module:read", "seance:read", "seance:write"})
     */
    private $contenu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fichier;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="seances")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $module;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSeance(): ?\DateTimeInterface
    {
        return $this->dateSeance;
    }

    public function setDateSeance(\DateTimeInterface $dateSeance): self
    {
        $this->dateSeance = $dateSeance;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }
}
