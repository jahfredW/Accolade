<?php

namespace App\Controller;

use Exception;
use App\Entity\Posts;
use App\Service\FacebookApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FbPostController extends AbstractController
{
    private $em;
    private $serializer;
    private $client;

    public function __construct(EntityManagerInterface $em,
    SerializerInterface $serializer, FacebookApiService $client)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->client = $client;
    }

    /**
    * @return Posts[]
    */
    public function __invoke(): array
    {
        $response = $this->client->fetchData()->toArray();        
        
        // Assurez-vous que la clé 'data' existe et est un tableau
        if (!isset($response['data']) || !is_array($response['data'])) {
            return new Response('Invalid data.', Response::HTTP_BAD_REQUEST);
        }
        
        // Étape 2: Dénormaliser les données pour créer des objets d'entité
        $posts = $this->serializer->deserialize(
            json_encode($response['data']), // Utiliser uniquement la clé 'data'
            Posts::class.'[]','json', ['groups' => ['post:test']] );

        usort($posts, function($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        // suppression des entrées 
        $this->em->getRepository(Posts::class)->deleteAll();
        
        // persister en base de donnée : 
        foreach( $posts as $post)
        {
            try {
                $this->em->persist($post);
            } catch( Exception $e){
                throw new Exception($e->getMessage());
                
            }
            
        }

        $this->em->flush();

        return $posts;
    }
}