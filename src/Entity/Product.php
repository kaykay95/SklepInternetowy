<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    public function __construct()
    {
        $this->negotiations = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }
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
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal")
     */
    private $price;

    /**
     * @ORM\Column(type="decimal")
     */
    private $minimalPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $negotiationRatio;

    /**
     * @ORM\Column(type="blob")
     */
    private $picture;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Negotiation", mappedBy="product")
     */
    private $negotiations;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getMinimalPrice()
    {
        return $this->minimalPrice;
    }

    public function setMinimalPrice($minimalPrice): void
    {
        $this->minimalPrice = $minimalPrice;
    }

    public function getNegotiationRatio()
    {
        return $this->negotiationRatio;
    }

    public function setNegotiationRatio($negotiationRatio): void
    {
        $this->negotiationRatio = $negotiationRatio;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return Collection|Negotiation[]
     */
    public function getNegotiations()
    {
        return $this->negotiations;
    }



}
