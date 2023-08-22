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
use Symfony\Component\HttpFoundation\Request;

class ArticleCount extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }


    public function __invoke(Request $request) : int
    {
       $online = $request->get('isActive');
       $conditions = [];

       if($online != null){
        $conditions = ['isActive' => $online === '1' ? true : false ];
       }

       return $this->em->getRepository(Article::class)->count($conditions);
       
    }
}