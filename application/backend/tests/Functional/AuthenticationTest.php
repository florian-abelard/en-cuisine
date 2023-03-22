<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class AuthenticationTest extends ApiTestCase
{
    public function testLoginWithValidCredentials(): void
    {
        $client = self::createClient();
        $client->request('POST', '/api/login', [
            'json' => ['username' => 'moflo', 'password' => 'MaudFlorian'],
        ]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            'username' => 'moflo',
        ]);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $client = self::createClient();
        $client->request('POST', '/api/login', [
            'json' => ['username' => 'moflo', 'password' => 'WrongPassword'],
        ]);

        self::assertResponseStatusCodeSame(401);
    }
}
