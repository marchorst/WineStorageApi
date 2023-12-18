<?php
namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\Model\SecurityScheme;
use ArrayObject;

class OpenApiFactory implements OpenApiFactoryInterface
{

    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        // Define your custom security scheme
        $securityScheme = new ArrayObject(
         [
            'custom_auth' => [
                'type' => 'apiKey',
                'in' => 'header',
                'name' => 'X-AUTH-TOKEN',
                // You can add more configurations as needed
            ],
        ]);
        // Add the security scheme to the global security section
        $s = $openApi->getComponents()->withSecuritySchemes($securityScheme);
        $openApi= $openApi->withComponents($s);
        return $openApi;
    }
}