<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $SubName = null;

    #[ORM\Column(nullable: true)]
    private ?float $SubPrice = null;

    #[ORM\OneToOne(mappedBy: 'subscribe', cascade: ['persist', 'remove'])]
    private ?Order $orders = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubName(): ?string
    {
        return $this->SubName;
    }

    public function setSubName(?string $SubName): self
    {
        $this->SubName = $SubName;

        return $this;
    }

    public function getSubPrice(): ?float
    {
        return $this->SubPrice;
    }

    public function setSubPrice(?float $SubPrice): self
    {
        $this->SubPrice = $SubPrice;

        return $this;
    }

    public function getOrders(): ?Order
    {
        return $this->orders;
    }

    public function setOrders(Order $orders): self
    {
        // set the owning side of the relation if necessary
        if ($orders->getSubscribe() !== $this) {
            $orders->setSubscribe($this);
        }

        $this->orders = $orders;

        return $this;
    }
}
