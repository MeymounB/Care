<?php

// AUTO GENERATED - DO NOT EDIT!
namespace App\Tests\Functional\Controller;

// use App\Entity\Advice;
// use App\Repository\AdviceRepository;
// use Symfony\Bundle\FrameworkBundle\KernelBrowser;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// class AdviceControllerTest extends WebTestCase
// {
//     private KernelBrowser $client;
//     private AdviceRepository $repository;
//     private string $path = '/advice/';

//     protected function setUp(): void
//     {
//         $this->client = static::createClient();
//         $this->repository = static::getContainer()->get('doctrine')->getRepository(Advice::class);

//         foreach ($this->repository->findAll() as $object) {
//             $this->repository->remove($object, true);
//         }
//     }

//     public function testIndex(): void
//     {
//         $crawler = $this->client->request('GET', $this->path);

//         self::assertResponseStatusCodeSame(200);
//         self::assertPageTitleContains('Advice index');

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
//             'advice[title]' => 'Testing',
//             'advice[description]' => 'Testing',
//             'advice[date]' => 'Testing',
//             'advice[createdAt]' => 'Testing',
//             'advice[updatedAt]' => 'Testing',
//             'advice[isPublic]' => 'Testing',
//             'advice[slug]' => 'Testing',
//             'advice[plants]' => 'Testing',
//             'advice[particular]' => 'Testing',
//             'advice[botanist]' => 'Testing',
//             'advice[status]' => 'Testing',
//         ]);

//         self::assertResponseRedirects('/advice/');

//         self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
//     }

//     public function testShow(): void
//     {
//         $this->markTestIncomplete();
//         $fixture = new Advice();
//         $fixture->setTitle('My Title');
//         $fixture->setDescription('My Title');
//         $fixture->setDate('My Title');
//         $fixture->setCreatedAt('My Title');
//         $fixture->setUpdatedAt('My Title');
//         $fixture->setIsPublic('My Title');
//         $fixture->setSlug('My Title');
//         $fixture->setPlants('My Title');
//         $fixture->setParticular('My Title');
//         $fixture->setBotanist('My Title');
//         $fixture->setStatus('My Title');

//         $this->repository->save($fixture, true);

//         $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

//         self::assertResponseStatusCodeSame(200);
//         self::assertPageTitleContains('Advice');

//         // Use assertions to check that the properties are properly displayed.
//     }

//     public function testEdit(): void
//     {
//         $this->markTestIncomplete();
//         $fixture = new Advice();
//         $fixture->setTitle('My Title');
//         $fixture->setDescription('My Title');
//         $fixture->setDate('My Title');
//         $fixture->setCreatedAt('My Title');
//         $fixture->setUpdatedAt('My Title');
//         $fixture->setIsPublic('My Title');
//         $fixture->setSlug('My Title');
//         $fixture->setPlants('My Title');
//         $fixture->setParticular('My Title');
//         $fixture->setBotanist('My Title');
//         $fixture->setStatus('My Title');

//         $this->repository->save($fixture, true);

//         $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

//         $this->client->submitForm('Update', [
//             'advice[title]' => 'Something New',
//             'advice[description]' => 'Something New',
//             'advice[date]' => 'Something New',
//             'advice[createdAt]' => 'Something New',
//             'advice[updatedAt]' => 'Something New',
//             'advice[isPublic]' => 'Something New',
//             'advice[slug]' => 'Something New',
//             'advice[plants]' => 'Something New',
//             'advice[particular]' => 'Something New',
//             'advice[botanist]' => 'Something New',
//             'advice[status]' => 'Something New',
//         ]);

//         self::assertResponseRedirects('/advice/');

//         $fixture = $this->repository->findAll();

//         self::assertSame('Something New', $fixture[0]->getTitle());
//         self::assertSame('Something New', $fixture[0]->getDescription());
//         self::assertSame('Something New', $fixture[0]->getDate());
//         self::assertSame('Something New', $fixture[0]->getCreatedAt());
//         self::assertSame('Something New', $fixture[0]->getUpdatedAt());
//         self::assertSame('Something New', $fixture[0]->getIsPublic());
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

//         $fixture = new Advice();
//         $fixture->setTitle('My Title');
//         $fixture->setDescription('My Title');
//         $fixture->setDate('My Title');
//         $fixture->setCreatedAt('My Title');
//         $fixture->setUpdatedAt('My Title');
//         $fixture->setIsPublic('My Title');
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
//         self::assertResponseRedirects('/advice/');
//     }
// }
