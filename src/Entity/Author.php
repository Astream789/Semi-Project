<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $AuName = null;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'author')]
    private Collection $Authors;

    public function __construct()
    {
        $this->Authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuName(): ?string
    {
        return $this->AuName;
    }

    public function setAuName(string $AuName): self
    {
        $this->AuName = $AuName;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getAuthors(): Collection
    {
        return $this->Authors;
    }

    public function addAuthor(Book $author): self
    {
        if (!$this->Authors->contains($author)) {
            $this->Authors->add($author);
            $author->addAuthor($this);
        }

        return $this;
    }

    public function removeAuthor(Book $author): self
    {
        if ($this->Authors->removeElement($author)) {
            $author->removeAuthor($this);
        }

        return $this;
    }
}
