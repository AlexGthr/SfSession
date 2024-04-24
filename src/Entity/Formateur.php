<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormateurRepository::class)]
class Formateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'formateur')]
    private Collection $referent;

    public function __construct()
    {
        $this->referent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getReferent(): Collection
    {
        return $this->referent;
    }

    public function addReferent(Session $referent): static
    {
        if (!$this->referent->contains($referent)) {
            $this->referent->add($referent);
            $referent->setFormateur($this);
        }

        return $this;
    }

    public function removeReferent(Session $referent): static
    {
        if ($this->referent->removeElement($referent)) {
            // set the owning side to null (unless already changed)
            if ($referent->getFormateur() === $this) {
                $referent->setFormateur(null);
            }
        }

        return $this;
    }

    public function getIdentite() {
        return $this->lastName . " " . $this->name;
    }
}
