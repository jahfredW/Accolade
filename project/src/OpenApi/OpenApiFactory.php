<?php 

namespace App\OpenApi;


use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        
        /** @var PathItem $path */
        foreach($openApi->getPaths()->getPaths() as $key => $path)
        {
            if($path->getGet() && $path->getGet()->getSummary()  === 'hidden'){
                $openApi->getPaths()->addPath($key, $path->withGet(null) );
            };
        }

       
        $openApi->getPaths()->addPath('/ping', new PathItem(null, 'Ping', null, new Operation(null, [], [], 'Répond')));
        
        return $openApi;
    }
}