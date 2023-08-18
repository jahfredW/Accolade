<?php

namespace App\Entity;

use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ORM\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArticleRepository;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['read:article:item']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['read:article:item']]
        ),
        new Put(
            denormalizationContext: ['groups' => ['put:article:item']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['post:article:item']]
        )
    ]
    
)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:article:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:article:item', 'put:article:item', 'post:article:item' ])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:article:item'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:article:item', 'put:article:item'])]
    private ?Category $category = null;

    #[ORM\PrePersist]
    public function setCreatedValue() : void 
    {
        $this->createdAt = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function computeSlug(SluggerInterface $slugger) :void
    {
        $this->slug = $slugger->slug($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function __toString() : string
    {
        return $this->name;
    }
}
