<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NegotiationRepository")
 */
class Negotiation
{
    public function __construct($user_id, $product_id, $date, $previousPrice)
    {
        $this->setUserId($user_id);
        $this->setProductId($product_id);
        $this->setDate($date);
        $this->setPreviousPrice($previousPrice);
        $this->setCorrectDiscount(false);
        $this->setCorrectExpression(false);
        $this->setCorrectExpressionByCategory(false);
        $this->setCategoryNotUsed(false);
        $this->setTransactionId(0);
        $this->setForVerification(false);
        $this->setAcceptedOffer(false);
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="negotiations")
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="negotiations")
     */
    private $product_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $previousPrice;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $desiredDiscount;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $finalPrice;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $correctDiscount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $correctExpression;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $categoryNotUsed;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $correctExpressionByCategory;


    /**
     * @ORM\ManyToOne(targetEntity="NegotiationCategory", inversedBy="negotiations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $negotiationCategory;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $forVerification;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $acceptedOffer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $transactionId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $competitorLink;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->product_id;
    }

    public function setProductId(Product $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
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

    public function getPreviousPrice()
    {
        return $this->previousPrice;
    }

    public function setPreviousPrice($previousPrice): void
    {
        $this->previousPrice = $previousPrice;
    }

    public function getDesiredDiscount()
    {
        return $this->desiredDiscount;
    }

    public function setDesiredDiscount($desiredDiscount): void
    {
        $this->desiredDiscount = $desiredDiscount;
    }

    public function getFinalPrice()
    {
        return $this->finalPrice;
    }

    public function setFinalPrice($finalPrice): void
    {
        $this->finalPrice = $finalPrice;
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

    public function getCorrectDiscount()
    {
        return $this->correctDiscount;
    }

    public function setCorrectDiscount($correctDiscount): void
    {
        $this->correctDiscount = $correctDiscount;
    }

    public function getCorrectExpression()
    {
        return $this->correctExpression;
    }

    public function setCorrectExpression($correctExpression): void
    {
        $this->correctExpression = $correctExpression;
    }

    public function getCategoryNotUsed()
    {
        return $this->categoryNotUsed;
    }

    public function setCategoryNotUsed($categoryNotUsed): void
    {
        $this->categoryNotUsed = $categoryNotUsed;
    }

    public function getNegotiationCategory()
    {
        return $this->negotiationCategory;
    }

    public function setNegotiationCategory($negotiationCategory): void
    {
        $this->negotiationCategory = $negotiationCategory;
    }

    public function getCorrectExpressionByCategory()
    {
        return $this->correctExpressionByCategory;
    }

    public function setCorrectExpressionByCategory($correctExpressionByCategory): void
    {
        $this->correctExpressionByCategory = $correctExpressionByCategory;
    }

    public function getForVerification()
    {
        return $this->forVerification;
    }

    public function setForVerification($forVerification): void
    {
        $this->forVerification = $forVerification;
    }

    public function getAcceptedOffer()
    {
        return $this->acceptedOffer;
    }


    public function setAcceptedOffer($acceptedOffer): void
    {
        $this->acceptedOffer = $acceptedOffer;
    }


    public function getTransactionId(): ?int
    {
        return $this->transactionId;
    }

    public function setTransactionId($transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function getCompetitorLink()
    {
        return $this->competitorLink;
    }

    public function setCompetitorLink($competitorLink): void
    {
        $this->competitorLink = $competitorLink;
    }


}
