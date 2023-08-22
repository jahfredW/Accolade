<?php
namespace App\State;

use App\Entity\Dependency;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;

class DependencyPostProcessor implements ProcessorInterface
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * {@inheritDoc}
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
      
        $this->em->persist($data);
        $this->em->flush();
        
        return $data;
    }
}