<?php

namespace App\Tests\Functional\Controller;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
	private string $directory;
	private Generator $faker;
	private KernelBrowser $client;
	private ?object $fileSystem;

	protected function setUp(): void
	{
		parent::setUp();
		$this->directory = dirname(__DIR__, 2) . "/docs";
		$this->faker = Factory::create();
		$this->client = static::createClient();
		$this->fileSystem = $this->client->getContainer()->get('Symfony\Component\Filesystem\Filesystem');

		$this->fileSystem->mkdir(dirname(__DIR__, 2) . "/docs/target");
	}
	public function testRegister(): void
	{
		$crawler = $this->client->request('GET', '/register');

		$this->assertResponseIsSuccessful();
		$form = $crawler->selectButton("S'inscrire")->form();
		$password = $this->faker->password(8);

		$form['particular_form[firstName]'] = $this->faker->firstName();
		$form['particular_form[lastName]'] = $this->faker->lastName();
		$form['particular_form[email]'] = $this->faker->email();
		$form['particular_form[cellphone]'] = $this->faker->phoneNumber();
		$form['particular_form[password][first]'] = $password;
		$form['particular_form[password][second]'] = $password;
		$form['particular_form[agreeTerms]'] = true;

		$this->client->submit($form);
		$this->assertResponseRedirects('/login');
	}

	public function testRegisterBotanist(): void
	{
		$manager = $this->client->getContainer()->get('doctrine')
			->getManager();
		$crawler = $this->client->request('GET', 'register/botanist');

		$email = $this->faker->email();

		$this->assertResponseIsSuccessful();
		$form = $crawler->filter("[type='submit']")->form();

		$password = $this->faker->password(8);

		$fake = $this->faker->file($this->directory . "/source", $this->directory . "/target");

		$form['botanist_form[certif]']->upload($fake);
		$form['botanist_form[firstName]'] = $this->faker->firstName();
		$form['botanist_form[lastName]'] = $this->faker->lastName();
		$form['botanist_form[email]'] = $email;
		$form['botanist_form[cellphone]'] = 123456789;
		$form['botanist_form[password][first]'] = $password;
		$form['botanist_form[password][second]'] = $password;
		$form['botanist_form[agreeTerms]'] = true;

		$this->client->submit($form);
		$this->assertResponseRedirects('/login');
		 $user = $manager
		 	->getRepository(User::class)
		 	->findOneBy(['email' => $email]);

		 $this->assertNotNull($user);
		 $this->assertSame($email, $user->getEmail());
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		// doing this is recommended to avoid memory leaks
		$path = $this->directory . "/target";
		// delete test file to clean up
		$this->fileSystem->remove($path);
		$this->client->disableReboot();
	}
}
