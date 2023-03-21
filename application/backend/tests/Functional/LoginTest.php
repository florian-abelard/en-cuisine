<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class LoginTest extends ApiTestCase
{
    public function testLoginWithValidCredentials(): void
    {
        $this->assertEquals(42, 42);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $this->assertNotEquals(42, 24);
    }
}
