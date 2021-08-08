<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class AuthenticationTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testLogin(): void
    {
        $client = self::createClient();

        $user = new User();
        $user->setEmail('vedran@milkovic.dev');
        $user->setPassword(
            static::getContainer()->get('security.password_encoder')->encodePassword($user, 'password')
        );

        $manager = static::getContainer()->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/tasker-api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'vedran@milkovic.dev',
                'password' => 'password',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);;

        // test not authorized
        $client->request('GET', '/tasker-api');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/tasker-api', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}
