<?php 

namespace App\EntityListener;

use App\Entity\Article;

use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(events: Events::prePersist, entity: Article::class)]
#[AsEntityListener(events: Events::preUpdate, entity: Article::class)]
class ArticleEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Article $article, LifecycleEventArgs $event): void 
    {
        $article->computeSlug($this->slugger);
    }
    
    public function preUpdate(Article $article, LifecycleEventArgs $event): void 
    {
        $article->computeSlug($this->slugger);
    }

}