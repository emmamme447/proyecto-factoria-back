<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ProjectTest4Test extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/period');

        $this->assertSelectorTextContains('h5', 'Períodos de prueba');
    }
}
