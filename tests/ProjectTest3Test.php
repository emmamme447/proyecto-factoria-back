<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ProjectTest3Test extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/email/to/manager');

        $this->assertSelectorTextContains('p', 'Por favor, introduce la dirección de correo electrónico para enviar un correo al responsable con un link para la evaluación final:');
    }
}

