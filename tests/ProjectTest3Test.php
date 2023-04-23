<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ProjectTest3Test extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/email/to/manager');

        $this->assertSelectorTextContains('label', 'link');
    }
}

