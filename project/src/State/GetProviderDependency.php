<?php
namespace App\State;
use Ramsey\Uuid\Uuid;
use App\Entity\Dependency;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;



final class GetProviderDependency implements ProviderInterface
{
    private string $rootPath;

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
     
    }
    
    /**
     * {@inheritDoc}
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $path = $this->rootPath . '/composer.json';
       
      
        // Vérifie si le fichier existe
        if (!file_exists($path)) {
            // Gérez l'erreur comme vous le souhaitez
            throw new \Exception("Fichier composer.json non trouvé");
        }

        $json = json_decode(file_get_contents($path), true);

        // Vérifie si le JSON est valide
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Gérez l'erreur comme vous le souhaitez
            throw new \Exception("Erreur de décodage JSON");
        }

      
        if($operation instanceof Get){
            foreach($json['require'] as $name => $version)
            {
                // utilsiation de ramsey/Uuid pour en générer un. 
                $uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $name)->toString();
                if($uuid === $uriVariables['uuid']){
                    return new Dependency($uuid, $name, $version);
                }

            }
            
        }
        
    return null;
        
    }
}