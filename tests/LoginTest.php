<?php

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $botanistRepository = self::getContainer()->get(UserRepository::class);

        $testUser = $botanistRepository->findOneBy(['email' => 'botanist@verified.com']);

        $this->assertNotNull($testUser);

        $client->loginUser($testUser);

        $client->request('GET', '/login');
        $this->assertResponseRedirects('/');
    }
}