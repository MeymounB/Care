<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService
{
    private ?FileType $type = null;

    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly ParameterBagInterface $params
    ) {
    }

    public function setType(FileType $type): void
    {
        $this->type = $type;
    }

    public function getFilename(?int $key, string $username, UploadedFile $file): array
    {
        $currentTime = time();

        if (null !== $key) {
            $newFilename = $this->getPrefixName() . '_' . $key . '_' . $username . '_' . $currentTime;
        } else {
            $newFilename = $this->getPrefixName() . '_' . $username . '_' . $currentTime;
        }

        return [
            'title' => $newFilename,
            'file' => $this->slugger->slug($newFilename) . '.' . $file->guessExtension(),
        ];
    }

    public function upload(string $filename, UploadedFile $file): void
    {
        $directory = $this->params->get($this->type->value . '_dir');
        try {
            $file->move($directory, $filename);
        } catch (FileException $e) {
            throw new \Exception('An error occurred during the upload of the file');
        }
    }

    private function getPrefixName(): string
    {
        return match ($this->type) {
            FileType::CERTIFICATE => 'document_certification',
            FileType::PHOTO => 'photo',
        };
    }
}
