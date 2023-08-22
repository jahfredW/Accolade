<?php

namespace App\Entity;

use ArrayObject;
use ORM\PrePersist;
use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use ORM\HasLifecycleCallbacks;
use App\Controller\ArticleCount;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\ArticleController;
use App\Repository\ArticleRepository;
use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\GetCollection;
// use Symfony\Component\Validator\Mapping\ClassMetadata;
use Webmozart\Assert\Assert as AssertAssert;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]

#[ApiResource(
    // validationContext:['groups' => ['a']],
    // paginationItemsPerPage: 2,
    // paginationMaximumItemsPerPage: 2,
    // paginationClientItemsPerPage: true,
    operations: [
        new GetCollection(
            name: 'count',
            uriTemplate: '/articles/count',
            controller: ArticleCount::class,
            paginationEnabled: false,
            filters: [],
            openapi: new Model\Operation(
                summary: "Récupère le nombre total d'articles",
                parameters: [
                    [
                        'in' => 'query',
                        'name' => 'isActive',
                        'schema' => [
                            'type' => 'integer',
                            'maximum' => 1,
                            'minimum' => 0,
                        ],
                        'description' => 'Filtre les articles en ligne'
                    ]
                    ],
                responses: [
                    '200' => [
                        'description' => 'OK',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'integer',
                                    'exemple' => 3
                                ]
                            ]
                        ]
                    ]
                ]
                
            )
            // normalizationContext: ['groups' => ['read:article:item']]
        ),
        new Get(
            normalizationContext: ['groups' => ['read:article:item']],
            controller: NotFoundAction::class,
            read: false,
            output: false,
            openapi: new Model\Operation(
                summary: "hidden",
            )
        ),
        
        new GetCollection(
            normalizationContext: ['groups' => ['read:article:item']]
        ),
        new Put(
            denormalizationContext: ['groups' => ['put:article:item']]
        ),
        new Post(
            validationContext: ['groups' => [Article::class, 'validationTest']],
            denormalizationContext: ['groups' => ['post:article:item']],
            normalizationContext: ['groups' => ['read:article:item'], 
            'openapi_definition_name' => 'Test']
        ),
        new Put(
            name: 'publication',
            uriTemplate:'/articles/{id}/publication',
            controller: ArticleController::class,
            // denormalizationContext: ['groups' => ['put:article:item']],
            write: false,
            openapi: new Model\Operation(
                summary: "Update isActive",
                requestBody: new Model\RequestBody(
                    required: false,
                    content: new ArrayObject(
                        [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => new ArrayObject([]), // Aucune propriété
                                    'additionalProperties' => false // Pas d'autres propriétés autorisées
                                ]
                            ]
                        ]
                    )
                )
            )
            // normalizationContext: ['groups' => ['read:article:item']]
        )
    ]
    
        ), 
        // ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'id' => 'exact'])
       ]
        
#[ORM\HasLifecycleCallbacks]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:article:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:article:item', 'post:article:item' ]),  Assert\Length(min: 5, groups: ['a']), 
    Assert\Regex([ "pattern" => '/^[^\d]*$/'], groups: ['a']) ]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:article:item'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column]
    #[Groups(['post:article:item', 'read:article:item'])]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'articles', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:article:item', 'post:article:item'])]
    private ?Category $category = null;

    // public static function loadValidatorMetadata(ClassMetadata $metadata): void 
    // {
    //     $metadata->addPropertyConstraint('name', new Assert\Regex(
    //         ['pattern' => '/^[^\d]*$/'], groups: ['a']
    //     ));
    // }

    // function de validation 
    public static function validationTest(self $article)
    {
        return ['a'];
    }

    #[ORM\PrePersist]
    public function setCreatedValue() : void 
    {
        $this->createdAt = new \DateTimeImmutable();
    }

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
