<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $AuName = null;

    #[ORM\ManyToOne(inversedBy: 'author')]
    private ?Book $book = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuName(): ?string
    {
        return $this->AuName;
    }

    public function setAuName(?string $AuName): self
    {
        $this->AuName = $AuName;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
