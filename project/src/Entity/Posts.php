<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostsRepository;
use App\Controller\FbPostController;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
#[ApiResource(
    
    operations : [
        new GetCollection(
            name :'FbPostController',
            uriTemplate: '/posts/facebook',
            controller : FbPostController::class,
            normalizationContext: ['groups' => ['read:collection', 'post:test']],
        ),
        new Get(
            normalizationContext: ['groups' => ['read:test']]
        ),
        new Post(
            normalizationContext: ['groups' => ['read:test']],
            denormalizationContext: ['groups' => ['post:test']]
            
        ),
        
    ]
)]
#[ORM\HasLifecycleCallbacks]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection', 'read:test'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:collection', 'post:test'])]
    private ?string $message = null;

    #[ORM\Column(length: 350, nullable: true)]
    #[Groups(['read:collection', 'post:test'])]
    #[SerializedName("full_picture")]
    private ?string $fullPicture = null;

    #[ORM\Column]
    #[SerializedName("created_time")]
    #[Groups(['read:collection', 'post:test'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function setCreatedValue() : void 
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getFullPicture(): ?string
    {
        return $this->fullPicture;
    }

    public function setFullPicture(?string $fullPicture): static
    {
        $this->fullPicture = $fullPicture;

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
}
