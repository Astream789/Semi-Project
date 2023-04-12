<?php

namespace App\Entity;

use App\Repository\ChapterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapterRepository::class)]
class Chapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Vol = null;

    #[ORM\Column(length: 4294967292, nullable: true)]
    private ?string $Content = null;

    #[ORM\ManyToOne(inversedBy: 'chapters')]
    private ?Book $book = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVol(): ?string
    {
        return $this->Vol;
    }

    public function setVol(?string $Vol): self
    {
        $this->Vol = $Vol;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(?string $Content): self
    {
        $this->Content = $Content;

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
