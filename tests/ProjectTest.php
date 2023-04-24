<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ProjectTest extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/email');

        $this->assertSelectorTextContains('h3', 'CORREO DE AUTOEVALUACIÓN PARA TRABAJADOR');
    }
}
