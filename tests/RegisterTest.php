<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;

ini_set('memory_limit', '256M');

class RegisterTest extends WebTestCase
{
	public function testRegister(): void
	{
		$faker = Factory::create();

		$client = static::createClient();
		$crawler = $client->request('GET', '/');

		$this->assertResponseIsSuccessful();

		$form = $crawler->selectButton("S'inscrire")->form();

		$password = $faker->password(8);

		$form['particular_form[firstName]'] = $faker->firstName();
		$form['particular_form[lastName]'] = $faker->lastName();
		$form['particular_form[email]'] = $faker->email();
		$form['particular_form[cellphone]'] = 123456789;
		$form['particular_form[password][first]'] = $password;
		$form['particular_form[password][second]'] = $password;
		$form['particular_form[agreeTerms]'] = true;

		$client->submit($form);

		$this->assertResponseRedirects('/login');
	}

	public function testRegisterBotanist(): void
	{
		$faker = Factory::create();

		$client = static::createClient();
		$crawler = $client->request('GET', '/botanist');

		$this->assertResponseIsSuccessful();

		$form = $crawler->selectButton("S'inscrire")->form();

		$password = $faker->password(8);
		$form['botanist_form[firstName]'] = $faker->firstName();
		$form['botanist_form[lastName]'] = $faker->lastName();
		$form['botanist_form[email]'] = $faker->email();
		$form['botanist_form[cellphone]'] = 123456789;
		$form['botanist_form[password][first]'] = $password;
		$form['botanist_form[password][second]'] = $password;
		$form['botanist_form[agreeTerms]'] = true;

		$client->submit($form);

		$this->assertResponseRedirects('/login');
	}
}
