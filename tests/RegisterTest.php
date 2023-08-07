<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;
use Faker\Generator;

ini_set('memory_limit', '1024M');

class RegisterTest extends WebTestCase
{
	private Generator $faker;
	protected function setUp(): void
	{
		parent::setUp();
		$this->faker = Factory::create();
	}

	public function testRegister(): void
	{

		$client = static::createClient();
		$crawler = $client->request('GET', '/register');

		$this->assertResponseIsSuccessful();

		$form = $crawler->selectButton("S'inscrire")->form();

		$password = $this->faker->password(8);

		$form['particular_form[firstName]'] = $this->faker->firstName();
		$form['particular_form[lastName]'] = $this->faker->lastName();
		$form['particular_form[email]'] = $this->faker->email();
		$form['particular_form[cellphone]'] = 123456789;
		$form['particular_form[password][first]'] = $password;
		$form['particular_form[password][second]'] = $password;
		$form['particular_form[agreeTerms]'] = true;

		$client->submit($form);

		$this->assertResponseRedirects('/login');
	}

	public function testRegisterBotanist(): void
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/botanist');

		$this->assertResponseIsSuccessful();

		$form = $crawler->selectButton("S'inscrire")->form();

		$password = $this->faker->password(8);
		$form['botanist_form[firstName]'] = $this->faker->firstName();
		$form['botanist_form[lastName]'] = $this->faker->lastName();
		$form['botanist_form[email]'] = $this->faker->email();
		$form['botanist_form[cellphone]'] = 123456789;
		$form['botanist_form[password][first]'] = $password;
		$form['botanist_form[password][second]'] = $password;
		$form['botanist_form[agreeTerms]'] = true;

		$client->submit($form);

		$this->assertResponseRedirects('/login');
	}
}
