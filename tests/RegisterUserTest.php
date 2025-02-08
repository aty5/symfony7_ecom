<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {

        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        $client->submitForm('Valider', [
                'register_user[email]' => 'test@exemple.fr',
                'register_user[plainPassword][first]' => '123456',
                'register_user[plainPassword][second]' => '123456',
                'register_user[firstname]' => 'test',
                'register_user[lastname]' => 'exemple'
            ]
        );

        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertSelectorExists('div:contains("Account created, you can connect!")');



    }
}
