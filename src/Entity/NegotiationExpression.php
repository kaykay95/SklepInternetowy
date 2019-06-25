<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NegotiationExpressionRepository")
 */
class NegotiationExpression
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="NegotiationCategory", inversedBy="negotiationExpressions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $negotiationCategory;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $expression;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNegotiationCategory(): ?NegotiationCategory
    {
        return $this->negotiationCategory;
    }

    public function setNegotiationCategory(?NegotiationCategory $negotiationCategory): self
    {
        $this->negotiationCategory = $negotiationCategory;

        return $this;
    }

    public function getExpression(): ?string
    {
        return $this->expression;
    }

    public function setExpression(string $expression): self
    {
        $this->expression = $expression;

        return $this;
    }
}
