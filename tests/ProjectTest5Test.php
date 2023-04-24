<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ProjectTest5Test extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/register');

        $this->assertSelectorTextContains('h1', 'Registro de empleados en período de prueba');
    }
}
