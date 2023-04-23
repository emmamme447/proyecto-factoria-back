<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ProjectTest2Test extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/employee');

        $this->assertSame('if', '($form->isSubmitted() && $form->isValid())');
    }
}
