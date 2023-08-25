<?php 

namespace App\OpenApi;


use ArrayObject;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
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

       
        $openApi->getPaths()->addPath('/ping', new PathItem(null, 'Ping', null, new Operation(null, ["ping"], [], 'Répond')));
        $openApi->getPaths()->addPath('/api/token/refresh', new PathItem(null, 'Refresh_Token', null, null, null, new Operation(null, ['auth'], ['200' => [
                        'description' => 'OK',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'string',
                                    'exemple' => 'edzedkzopekdopze',
                                ]
                            ]
                        ]
                    ]], null, null, null, null, new RequestBody(
            required: false,
                    content: new ArrayObject(
                        [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => new ArrayObject(["refresh_token" => ['type' => 'string']]), // Aucune propriété
                                    'additionalProperties' => false // Pas d'autres propriétés autorisées
                                ]
                            ]
                        ]
                    )
                
            
        ),)));
        
        return $openApi;
    }
}