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

    #[ORM\Column(length: 255)]
    private ?string $TgName = null;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'tag')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTgName(): ?string
    {
        return $this->TgName;
    }

    public function setTgName(string $TgName): self
    {
        $this->TgName = $TgName;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Book $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addTag($this);
        }

        return $this;
    }

    public function removeTag(Book $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeTag($this);
        }

        return $this;
    }
}
