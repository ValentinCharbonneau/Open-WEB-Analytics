<?php

namespace App\Entity\Location;

use App\Entity\Visitor\Visitor;
use App\Repository\Location\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\Column(length: 32)]
    private ?string $id = null;

    #[ORM\Column(length: 5)]
    private ?string $shortName = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Visitor::class)]
    private Collection $visitors;

    public function __construct()
    {
        $this->visitors = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setUuid(string $newUuid): self
    {
        if ($this->id == null)
        {
            $this->id = $newUuid;
        }

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return Collection<int, Visitor>
     */
    public function getVisitors(): Collection
    {
        return $this->visitors;
    }

    public function addVisitor(Visitor $visitor): self
    {
        if (!$this->visitors->contains($visitor)) {
            $this->visitors->add($visitor);
            $visitor->setCountry($this);
        }

        return $this;
    }

    public function removeVisitor(Visitor $visitor): self
    {
        if ($this->visitors->removeElement($visitor)) {
            // set the owning side to null (unless already changed)
            if ($visitor->getCountry() === $this) {
                $visitor->setCountry(null);
            }
        }

        return $this;
    }
}
