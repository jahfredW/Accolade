<?php 

namespace App\EntityListener;

use App\Entity\Posts;

use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::prePersist, entity: Posts::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Posts::class)]
class PostsEntityListener
{
    

    public function __construct()
    {
        
    }

    public function prePersist(Posts $post, LifecycleEventArgs $event): void 
    {
   
    }
    
    public function preUpdate(Posts $post, LifecycleEventArgs $event): void 
    {
   
    }

}