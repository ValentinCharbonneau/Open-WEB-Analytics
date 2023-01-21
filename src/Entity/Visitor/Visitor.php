<?php

namespace App\Entity\Visitor;

use App\Repository\Visitor\VisitorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
class Visitor
{
    #[ORM\Id]
    #[ORM\Column(length: 32)]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'visitors')]
    private ?Site $site = null;

    #[ORM\OneToMany(mappedBy: 'Visitor', targetEntity: Request::class)]
    private Collection $requests;

    #[ORM\ManyToOne(inversedBy: 'Visitors')]
    private ?System $system = null;

    #[ORM\ManyToOne(inversedBy: 'Visitors')]
    private ?Langage $langage = null;

    #[ORM\ManyToOne(inversedBy: 'Visitors')]
    private ?Origin $origin = null;

    #[ORM\ManyToOne(inversedBy: 'Visitors')]
    private ?Browser $browser = null;

    #[ORM\ManyToOne(inversedBy: 'visitors')]
    private ?Country $country = null;

    #[ORM\ManyToOne(inversedBy: 'visitors')]
    private ?Departement $departement = null;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
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

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): self
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setVisitor($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getVisitor() === $this) {
                $request->setVisitor(null);
            }
        }

        return $this;
    }

    public function getSystem(): ?System
    {
        return $this->system;
    }

    public function setSystem(?System $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getLangage(): ?Langage
    {
        return $this->langage;
    }

    public function setLangage(?Langage $langage): self
    {
        $this->langage = $langage;

        return $this;
    }

    public function getOrigin(): ?Origin
    {
        return $this->origin;
    }

    public function setOrigin(?Origin $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getBrowser(): ?Browser
    {
        return $this->browser;
    }

    public function setBrowser(?Browser $browser): self
    {
        $this->browser = $browser;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }
}
