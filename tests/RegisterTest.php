<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;

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

		$form['registration_form[firstName]'] = $faker->firstName();
		$form['registration_form[lastName]'] = $faker->lastName();
		$form['registration_form[email]'] = $faker->email();
		$form['registration_form[cellphone]'] = 123456789;
		$form['registration_form[password][first]'] = $password;
		$form['registration_form[password][second]'] = $password;
		$form['registration_form[agreeTerms]'] = true;

		$client->submit($form);

		$this->assertResponseRedirects('/login');
	}

	public function testRegisterDoctor(): void
	{
		$faker = Factory::create();

		$client = static::createClient();
		$crawler = $client->request('GET', '/botanist');

		$this->assertResponseIsSuccessful();

		$form = $crawler->selectButton("S'inscrire")->form();

		$password = $faker->password();
		$form['botanist_registration[firstName]'] = $faker->firstName();
		$form['botanist_registration[lastName]'] = $faker->lastName();
		$form['botanist_registration[email]'] = $faker->email();
		$form['botanist_registration[cellphone]'] = 123456789;
		$form['botanist_registration[password][first]'] = $password;
		$form['botanist_registration[password][second]'] = $password;
		$form['botanist_registration[agreeTerms]'] = true;

		$client->submit($form);

		$this->assertResponseRedirects('/login');
	}
}