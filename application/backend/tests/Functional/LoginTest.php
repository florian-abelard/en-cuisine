<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class LoginTest extends ApiTestCase
{
    public function testLoginWithValidCredentials(): void
    {
        $client = self::createClient();
        $client->request('POST', '/users/login', [
            'json' => ['username' => 'moflo', 'password' => 'MaudFlorian'],
        ]);

        self::assertResponseIsSuccessful();
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $client = self::createClient();
        $client->request('POST', '/users/login', [
            'json' => ['username' => 'moflo', 'password' => 'WrongPassword'],
        ]);

        self::assertResponseStatusCodeSame(401);
    }
}
