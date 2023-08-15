<?php

// AUTO GENERATED - DO NOT EDIT!
namespace App\Tests\Functional\Controller;

// use App\Entity\Appointment;
// use App\Repository\AppointmentRepository;
// use Symfony\Bundle\FrameworkBundle\KernelBrowser;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// class AppointmentControllerTest extends WebTestCase
// {
//     private KernelBrowser $client;
//     private AppointmentRepository $repository;
//     private string $path = '/appointment/';

//     protected function setUp(): void
//     {
//         $this->client = static::createClient();
//         $this->repository = static::getContainer()->get('doctrine')->getRepository(Appointment::class);

//         foreach ($this->repository->findAll() as $object) {
//             $this->repository->remove($object, true);
//         }
//     }

//     public function testIndex(): void
//     {
//         $crawler = $this->client->request('GET', $this->path);

//         self::assertResponseStatusCodeSame(200);
//         self::assertPageTitleContains('Appointment index');

//         // Use the $crawler to perform additional assertions e.g.
//         // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
//     }

//     public function testNew(): void
//     {
//         $originalNumObjectsInRepository = count($this->repository->findAll());

//         $this->markTestIncomplete();
//         $this->client->request('GET', sprintf('%snew', $this->path));

//         self::assertResponseStatusCodeSame(200);

//         $this->client->submitForm('Save', [
//             'appointment[title]' => 'Testing',
//             'appointment[description]' => 'Testing',
//             'appointment[date]' => 'Testing',
//             'appointment[createdAt]' => 'Testing',
//             'appointment[updatedAt]' => 'Testing',
//             'appointment[plannedAt]' => 'Testing',
//             'appointment[isPresential]' => 'Testing',
//             'appointment[adress]' => 'Testing',
//             'appointment[link]' => 'Testing',
//             'appointment[slug]' => 'Testing',
//             'appointment[plants]' => 'Testing',
//             'appointment[particular]' => 'Testing',
//             'appointment[botanist]' => 'Testing',
//             'appointment[status]' => 'Testing',
//         ]);

//         self::assertResponseRedirects('/appointment/');

//         self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
//     }

//     public function testShow(): void
//     {
//         $this->markTestIncomplete();
//         $fixture = new Appointment();
//         $fixture->setTitle('My Title');
//         $fixture->setDescription('My Title');
//         $fixture->setDate('My Title');
//         $fixture->setCreatedAt('My Title');
//         $fixture->setUpdatedAt('My Title');
//         $fixture->setPlannedAt('My Title');
//         $fixture->setIsPresential('My Title');
//         $fixture->setAdress('My Title');
//         $fixture->setLink('My Title');
//         $fixture->setSlug('My Title');
//         $fixture->setPlants('My Title');
//         $fixture->setParticular('My Title');
//         $fixture->setBotanist('My Title');
//         $fixture->setStatus('My Title');

//         $this->repository->save($fixture, true);

//         $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

//         self::assertResponseStatusCodeSame(200);
//         self::assertPageTitleContains('Appointment');

//         // Use assertions to check that the properties are properly displayed.
//     }

//     public function testEdit(): void
//     {
//         $this->markTestIncomplete();
//         $fixture = new Appointment();
//         $fixture->setTitle('My Title');
//         $fixture->setDescription('My Title');
//         $fixture->setDate('My Title');
//         $fixture->setCreatedAt('My Title');
//         $fixture->setUpdatedAt('My Title');
//         $fixture->setPlannedAt('My Title');
//         $fixture->setIsPresential('My Title');
//         $fixture->setAdress('My Title');
//         $fixture->setLink('My Title');
//         $fixture->setSlug('My Title');
//         $fixture->setPlants('My Title');
//         $fixture->setParticular('My Title');
//         $fixture->setBotanist('My Title');
//         $fixture->setStatus('My Title');

//         $this->repository->save($fixture, true);

//         $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

//         $this->client->submitForm('Update', [
//             'appointment[title]' => 'Something New',
//             'appointment[description]' => 'Something New',
//             'appointment[date]' => 'Something New',
//             'appointment[createdAt]' => 'Something New',
//             'appointment[updatedAt]' => 'Something New',
//             'appointment[plannedAt]' => 'Something New',
//             'appointment[isPresential]' => 'Something New',
//             'appointment[adress]' => 'Something New',
//             'appointment[link]' => 'Something New',
//             'appointment[slug]' => 'Something New',
//             'appointment[plants]' => 'Something New',
//             'appointment[particular]' => 'Something New',
//             'appointment[botanist]' => 'Something New',
//             'appointment[status]' => 'Something New',
//         ]);

//         self::assertResponseRedirects('/appointment/');

//         $fixture = $this->repository->findAll();

//         self::assertSame('Something New', $fixture[0]->getTitle());
//         self::assertSame('Something New', $fixture[0]->getDescription());
//         self::assertSame('Something New', $fixture[0]->getDate());
//         self::assertSame('Something New', $fixture[0]->getCreatedAt());
//         self::assertSame('Something New', $fixture[0]->getUpdatedAt());
//         self::assertSame('Something New', $fixture[0]->getPlannedAt());
//         self::assertSame('Something New', $fixture[0]->getIsPresential());
//         self::assertSame('Something New', $fixture[0]->getAdress());
//         self::assertSame('Something New', $fixture[0]->getLink());
//         self::assertSame('Something New', $fixture[0]->getSlug());
//         self::assertSame('Something New', $fixture[0]->getPlants());
//         self::assertSame('Something New', $fixture[0]->getParticular());
//         self::assertSame('Something New', $fixture[0]->getBotanist());
//         self::assertSame('Something New', $fixture[0]->getStatus());
//     }

//     public function testRemove(): void
//     {
//         $this->markTestIncomplete();

//         $originalNumObjectsInRepository = count($this->repository->findAll());

//         $fixture = new Appointment();
//         $fixture->setTitle('My Title');
//         $fixture->setDescription('My Title');
//         $fixture->setDate('My Title');
//         $fixture->setCreatedAt('My Title');
//         $fixture->setUpdatedAt('My Title');
//         $fixture->setPlannedAt('My Title');
//         $fixture->setIsPresential('My Title');
//         $fixture->setAdress('My Title');
//         $fixture->setLink('My Title');
//         $fixture->setSlug('My Title');
//         $fixture->setPlants('My Title');
//         $fixture->setParticular('My Title');
//         $fixture->setBotanist('My Title');
//         $fixture->setStatus('My Title');

//         $this->repository->save($fixture, true);

//         self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

//         $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
//         $this->client->submitForm('Delete');

//         self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
//         self::assertResponseRedirects('/appointment/');
//     }
// }
