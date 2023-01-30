<?php

namespace App\Entity\Site;

use App\Entity\Visitor\Visitor;
use App\Repository\Site\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\Column(length: 32)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: SiteStatus::class)]
    private Collection $siteStatuses;

    #[ORM\OneToMany(mappedBy: 'Site', targetEntity: Visitor::class)]
    private Collection $visitors;

    public function __construct()
    {
        $this->siteStatuses = new ArrayCollection();
        $this->visitors = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setUuid(string $newUuid): self
    {
        if ($this->id == null) {
            $this->id = $newUuid;
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection<int, SiteStatus>
     */
    public function getSiteStatuses(): Collection
    {
        return $this->siteStatuses;
    }

    public function addSiteStatus(SiteStatus $siteStatus): self
    {
        if (!$this->siteStatuses->contains($siteStatus)) {
            $this->siteStatuses->add($siteStatus);
            $siteStatus->setSite($this);
        }

        return $this;
    }

    public function removeSiteStatus(SiteStatus $siteStatus): self
    {
        if ($this->siteStatuses->removeElement($siteStatus)) {
            // set the owning side to null (unless already changed)
            if ($siteStatus->getSite() === $this) {
                $siteStatus->setSite(null);
            }
        }

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
            $visitor->setSite($this);
        }

        return $this;
    }

    public function removeVisitor(Visitor $visitor): self
    {
        if ($this->visitors->removeElement($visitor)) {
            // set the owning side to null (unless already changed)
            if ($visitor->getSite() === $this) {
                $visitor->setSite(null);
            }
        }

        return $this;
    }
}
