<?php

namespace App\Tests\Service;

use App\Service\FileType;
use App\Service\FileUploaderService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderServiceTest extends KernelTestCase
{
	const PATH = __DIR__ . "/fixtures/";
	const FILENAME = 'hello.pdf';
	const USERNAME = 'username';
	private FileUploaderService $service;

	protected function setUp(): void
	{
		self::bootKernel();
		$this->service = self::getContainer()->get('App\Service\FileUploaderService');
	}

	public function testGetFilename(): void
	{
		$uploadedFile = new UploadedFile(self::PATH . self::FILENAME, self::FILENAME, 'application/pdf', null, true);

		$this->assertNotNull($uploadedFile);

		$this->service->setType(FileType::CERTIFICATE); // set upload type

		$name = $this->service->getFilename(0, self::USERNAME, $uploadedFile);

		$this->assertIsArray($name);
		$this->assertArrayHasKey('file', $name);
	}

	public function testGetValidName(): void
	{
		$uploadedFile = new UploadedFile(self::PATH . self::FILENAME, self::FILENAME, 'application/pdf', null, true);

		$this->assertNotNull($uploadedFile);

		$this->service->setType(FileType::CERTIFICATE);

		$name = $this->service->getFilename(0, self::USERNAME, $uploadedFile);

		$this->assertIsArray($name);
		$this->assertArrayHasKey('title', $name);
		$this->assertArrayHasKey('file', $name);
		$this->assertStringContainsString(self::USERNAME, $name['file']);
		$this->assertStringContainsString('0', $name['file']);
	}

	public function testGetNameNoType(): void
	{
		$uploadedFile = new UploadedFile(self::PATH . self::FILENAME, self::FILENAME, 'application/pdf', null, true);

		$this->assertNotNull($uploadedFile);

		$this->expectException(\UnhandledMatchError::class);

		$name = $this->service->getFilename(0, self::USERNAME, $uploadedFile);
	}

	public function testGetValidNameWithoutKey(): void
	{
		$uploadedFile = new UploadedFile(self::PATH . self::FILENAME, self::FILENAME, 'application/pdf', null, true);

		$this->assertNotNull($uploadedFile);

		$this->service->setType(FileType::CERTIFICATE);

		$name = $this->service->getFilename(null, self::USERNAME, $uploadedFile);

		$this->assertIsArray($name);
		$this->assertArrayHasKey('title', $name);
		$this->assertArrayHasKey('file', $name);
		$this->assertIsString($name['title']);
		$this->assertIsString($name['file']);
		$this->assertStringContainsString(self::USERNAME, $name['file']);
		$this->assertStringContainsString('document-certification', $name['file']);
	}

	public function testUpload(): void
	{
		// copy/paste the mock file because it will be moved to another folder
		$file = self::getContainer()->get('Symfony\Component\Filesystem\Filesystem');
		$file->copy(self::PATH . 'hello.pdf', self::PATH . '/hello1.pdf');

		// get the mock file
		$uploadedFile = new UploadedFile(self::PATH . 'hello1.pdf', 'hello1.pdf', 'application/pdf', null, true);

		$this->assertNotNull($uploadedFile);

		$this->service->setType(FileType::CERTIFICATE); // set upload type

		$name = $this->service->getFilename(null, self::USERNAME, $uploadedFile);

		$this->service->upload($name['file'], $uploadedFile);

		// assert that the file exists => it has been moved successfully
		$this->assertFileExists(dirname(__DIR__) . '/../uploads/certifications/' . $name['file']);

		// delete test file to clean up
		$file->remove(dirname(__DIR__) . '/../uploads/certifications/' . $name['file']);
	}
}
