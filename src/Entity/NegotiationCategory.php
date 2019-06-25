<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NegotiationTypeRepository")
 */
class NegotiationCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NegotiationExpression", mappedBy="NegotiationCategory", orphanRemoval=true)
     */
    private $negotiationExpressions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NegotiationExpression", mappedBy="NegotiationCategory", orphanRemoval=false)
     */
    private $negotiations;

    public function __construct()
    {
        $this->negotiationExpressions = new ArrayCollection();
        $this->negotiations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function __toString() {
        return $this->getName();
    }

    /**
     * @return Collection|NegotiationExpression[]
     */
    public function getNegotiationExpressions(): Collection
    {
        return $this->negotiationExpressions;
    }

    public function addNegotiation(Negotiation $negotiation): self
    {
        if (!$this->negotiations->contains($negotiation)) {
            $this->negotiations[] = $negotiation;
            $negotiation->setNegotiationCategory($this);
        }

        return $this;
    }

    public function getNegotiations(): Collection
    {
        return $this->negotiations;
    }

    public function addNegotiationExpression(NegotiationExpression $negotiationExpression): self
    {
        if (!$this->negotiationExpressions->contains($negotiationExpression)) {
            $this->negotiationExpressions[] = $negotiationExpression;
            $negotiationExpression->setNegotiationCategory($this);
        }

        return $this;
    }

    public function removeNegotiationExpression(NegotiationExpression $negotiationExpression): self
    {
        if ($this->negotiationExpressions->contains($negotiationExpression)) {
            $this->negotiationExpressions->removeElement($negotiationExpression);
            // set the owning side to null (unless already changed)
            if ($negotiationExpression->getNegotiationCategory() === $this) {
                $negotiationExpression->setNegotiationCategory(null);
            }
        }

        return $this;
    }
}
