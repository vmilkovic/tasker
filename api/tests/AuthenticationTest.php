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
        $user->setUsername('vmilkovic');
        $user->setPassword(
            static::getContainer()->get('security.user_password_hasher')->hashPassword($user, 'password')
        );

        $manager = static::getContainer()->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // set tokens
        $client->request('POST', '/tasker-api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'vedran@milkovic.dev',
                'password' => 'password',
            ],
        ]);

        // test if tokens are set
        $this->assertResponseStatusCodeSame(204);

        // test authorized
        $client->request('GET', '/tasker-api');
        $this->assertResponseIsSuccessful();
    }
}
