<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

ini_set('memory_limit', '1024M');

class LoginTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $botanistRepository = self::getContainer()->get(UserRepository::class);

        $testUser = $botanistRepository->findOneBy(['email' => 'botanist@verified.com']);

        $this->assertNotNull($testUser);

        $client->loginUser($testUser);

        $client->request('GET', '/dashboard');
        $this->assertResponseRedirects('http://localhost/dashboard/', 301);
    }
}
