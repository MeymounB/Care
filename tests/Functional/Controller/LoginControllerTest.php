<?php

namespace App\Tests\Functional\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class LoginControllerTest extends WebTestCase
{
    public function testUserLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Select the form and fill in some values
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'particular@verified.com';
        $form['_password'] = 'safeP@ssword123';

        $client->submit($form);

        // Verify that the response is a redirect to the homepage
        $this->assertResponseRedirects('/homepage', 302, 'User should be redirected after a successful login.');

        // Follow the redirect
        $crawler = $client->followRedirect();
        $this->assertStringContainsString('Bienvenue', $crawler->filter('body')->text(), 'The homepage should display a welcome message after login.');
    }

    // User tries to login while already logged in
    public function testRedirectWhenAlreadyLoggedIn(): void
    {
        $client = static::createClient();
        $botanistRepository = self::getContainer()->get(UserRepository::class);

        $testUser = $botanistRepository->findOneBy(['email' => 'botanist@verified.com']);

        $this->assertNotNull($testUser);
        $client->loginUser($testUser);

        $client->request('GET', '/login');
        // @TODO: The user should be redirected to the homepage, for now just testing via logout action
        $this->assertResponseRedirects('/logout', 302, 'User should be redirected to the logout page when trying to access the login page while already logged in.');
    }
}
