<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ProjectTest2Test extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/employee');

        $this->assertSelectorTextContains('h5', 'Tabla Empleados');
    }
}
