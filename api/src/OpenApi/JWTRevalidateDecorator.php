<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

final class JWTRevalidateDecorator implements OpenApiFactoryInterface
{

    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Refresher'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'refreshToken' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);

        $schemas['Refresher Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'refreshToken' => [
                    'type' => 'string',
                    'example' => 'f6a611282c5fc980ddfc970fcf3029b6e773068df51797c3ef272b47d1593bdd40077aadab35052021a78f5810e3ba0a9b089da37f3c2b36074e4b7e23585b8b',
                ]
            ],
        ]);

        $pathItem = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Reset Authentication Token'],
                responses: [
                    '200' => [
                        'description' => 'Reset JWT token in httpOnly cookie and get new refresher token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Refresher',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Set new JWT token with refresher token.',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token with refresher token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Refresher Credentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/tasker-api/token/refresh', $pathItem);

        return $openApi;
    }
}
