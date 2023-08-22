<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em,
    SerializerInterface $serializer)
    {
        $this->em = $em;
    }


    public function __invoke(Article $article): Article
    {
       $article->isIsActive() == false ? $article->setIsActive(true) : $article->setIsActive(false);

       $this->em->persist($article);
       $this->em->flush();

       return $article;
    }
}