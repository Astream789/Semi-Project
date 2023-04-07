<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $TgName = null;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'tag')]
    private Collection $Books;

    public function __construct()
    {
        $this->Books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTgName(): ?string
    {
        return $this->TgName;
    }

    public function setTgName(?string $TgName): self
    {
        $this->TgName = $TgName;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->Books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->Books->contains($book)) {
            $this->Books->add($book);
            $book->addTag($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->Books->removeElement($book)) {
            $book->removeTag($this);
        }

        return $this;
    }
}
