<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'Books')]
    private Collection $tag;

    #[ORM\OneToMany(mappedBy: 'Books', targetEntity: Author::class)]
    private Collection $author;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $BkName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Detail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
        $this->author = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author->add($author);
            $author->setBooks($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->author->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getBooks() === $this) {
                $author->setBooks(null);
            }
        }

        return $this;
    }

    public function getBkName(): ?string
    {
        return $this->BkName;
    }

    public function setBkName(?string $BkName): self
    {
        $this->BkName = $BkName;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->Detail;
    }

    public function setDetail(?string $Detail): self
    {
        $this->Detail = $Detail;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }
}
