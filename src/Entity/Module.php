<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *              collectionOperations={"get"},
 *              itemOperations={"get"},
 *              normalizationContext={"groups"={"module:read"}},
 *              denormalizationContext={"groups"={"module:write"}}
 * )
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"module:read", "formation:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"module:read", "formation:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Groups({"module:read", "formation:read"})
     */
    private $nbHeures;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    /**
     * @ORM\OneToMany(targetEntity=Seance::class, mappedBy="module")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"module:read", "seance:read"})
     */
    private $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
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

    public function getNbHeures(): ?string
    {
        return $this->nbHeures;
    }

    public function setNbHeures(string $nbHeures): self
    {
        $this->nbHeures = $nbHeures;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection|Seance[]
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): self
    {
        if (!$this->seances->contains($seance)) {
            $this->seances[] = $seance;
            $seance->setModule($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getModule() === $this) {
                $seance->setModule(null);
            }
        }

        return $this;
    }
}
