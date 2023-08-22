<?php

namespace App\Entity;

use Model;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Post;
use App\State\GetProviderDependency;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\OpenApi\Model\Example;
use App\State\DependencyPostProcessor;
use ApiPlatform\Metadata\GetCollection;
use App\State\CollectionProviderDependency;

#[ApiResource()]
#[ORM\Entity()]
#[GetCollection (provider: CollectionProviderDependency::class, paginationEnabled:false )]
#[Get(provider: GetProviderDependency::class, paginationEnabled:false )]
#[Post(processor: DependencyPostProcessor::class, paginationEnabled:false )]
class Dependency 
{
    #[ApiProperty(
        identifier: true
    )]
    #[ORM\Id()]
    #[ORM\Column(length: 255)]
    private string $uuid;

    #[ApiProperty(
        description: 'Nom de la dépendance',
    )]
    #[ORM\Column(length: 255)]
    private string $name;

    #[ApiProperty(
        description: 'Version de la dépendance',
        openapiContext:[
            'example' => '1.0.0'
        ]

    )]
    #[ORM\Column(length: 255)]
    private string $version;

    public function __construct(string $uuid, string $name, string $version)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->version = $version;
    }


        /**
         * Get the value of uuid
         */ 
        public function getUuid() : string
        {
                return $this->uuid;
        }

        /**
         * Get the value of name
         */ 
        public function getName() : string
        {
                return $this->name;
        }

        /**
         * Get the value of version
         */ 
        public function getVersion() : string
        {
                return $this->version;
        }
}