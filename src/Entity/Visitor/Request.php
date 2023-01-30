<?php

namespace App\Entity\Visitor;

use App\Repository\Visitor\RequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\Column(length: 32)]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    private ?Visitor $visitor = null;

    #[ORM\ManyToOne(inversedBy: 'Request')]
    private ?Page $page = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

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

    public function getVisitor(): ?Visitor
    {
        return $this->visitor;
    }

    public function setVisitor(?Visitor $visitor): self
    {
        $this->visitor = $visitor;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
}
