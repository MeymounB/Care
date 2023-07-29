<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Advice;
use App\Entity\Appointment;
use App\Entity\Botanist;
use App\Entity\Certificate;
use App\Entity\Comment;
use App\Entity\Particular;
use App\Entity\Photo;
use App\Entity\Plant;
use App\Entity\Status;
// use for passwordEncoder ?
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('en_US');

        // Create types of Status
        $status = $this->createStatuses($manager);

        // Create 10 botanists
        $botanists = $this->createBotanists($faker, $manager);

        // Create 20 particular
        $particulars = $this->createParticulars($faker, $manager);

        // Create 50 certificates for botanists
        $this->createCertificates($faker, $manager);

        // Create 10 appointments
        $appointments = $this->createAppts($faker, $manager, $status, $botanists, $particulars);

        // Create 10 advices
        $advices = $this->createAdvices($faker, $manager, $status, $botanists, $particulars);

        // Create 20 plants
        $plants = $this->createPlants($faker, $manager, $status, $appointments, $advices);

        // Create 20 comments
        $comments = $this->createComments($faker, $manager);

        foreach ($plants as $plant) {
            $photo = new Photo();
            $photo
                ->setThumbnail('https://picsum.photos/200/300')
                ->setSmallThumbnail('https://picsum.photos/100/150')
                ->setCreatedAt(new \DateTimeImmutable('now'))
                ->setPlant($plant);

            $manager->persist($photo);
        }

        foreach ($particulars as $particular) {
            $address = new Address();
            $address
                ->setStreet($faker->streetAddress)
                ->setZipCode($faker->randomNumber(5))
                ->setCity($faker->city)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setParticular($particular);

            $manager->persist($address);
        }

        $manager->flush();
    }

    private function createStatuses(ObjectManager $manager): array
    {
        $status = [];
        foreach (Status::STATUS as $value) {
            $oneStatus = new Status();
            $oneStatus->setName($value);
            $manager->persist($oneStatus);
            $status[] = $oneStatus;
        }

        return $status;
    }

    private function createBotanists(Generator $faker, ObjectManager $manager): array
    {
        $botanists = [];
        for ($i = 0; $i < 10; ++$i) {
            $botanist = new Botanist();
            $botanist
                ->setEmail($faker->unique()->safeEmail)
                ->setPassword($faker->password)
                ->setFirstName($faker->firstName)
                ->setAvatar('https://picsum.photos/200/300')
                ->setLastName($faker->lastName)
                ->setCellphone($faker->phoneNumber);

            $manager->persist($botanist);
            $botanists[] = $botanist;
            $this->addReference(Botanist::class.'_'.$i, $botanist);
        }

        return $botanists;
    }

    private function createParticulars(Generator $faker, ObjectManager $manager): array
    {
        $particulars = [];
        for ($i = 0; $i < 20; ++$i) {
            $particular = new Particular();
            $particular
                ->setEmail($faker->unique()->safeEmail)
                ->setPassword($faker->password)
                ->setFirstName($faker->firstName)
                ->setAvatar('https://picsum.photos/200/300')
                ->setLastName($faker->lastName)
                ->setCellphone($faker->phoneNumber);

            $this->createAddress($faker, $manager, $particular);

            $manager->persist($particular);
            $particulars[] = $particular;
            $this->addReference(Particular::class.'_'.$i, $particular);
        }

        return $particulars;
    }

    private function createCertificates(Generator $faker, ObjectManager $manager): void
    {
        for ($i = 1; $i <= 50; ++$i) {
            $certificate = new Certificate();
            $certificate->setTitle($faker->sentence(4))
                ->setState($faker->randomElement(Certificate::getPossibleStates()))
                ->setCertificateFile('/path/to/file')
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            if ($this->hasReference(Botanist::class.'_'.$i)) {
                $certificate->setBotanist($this->getReference(Botanist::class.'_'.rand(0, 9)));
            }

            $manager->persist($certificate);
        }
    }

    private function createAppts(Generator $faker, ObjectManager $manager, array $status, array $botanists, array $particulars): array
    {
        $appointments = [];
        for ($i = 0; $i < 10; ++$i) {
            // Appointment
            $appointment = new Appointment();
            $appointment
                ->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph)
                ->setPlannedAt(new \DateTime('now + '."{$i} days"))
                ->setIsPresential($faker->boolean)
                ->setAddress($faker->address)
                ->setLink($faker->url)
                ->setCreatedAt(new \DateTimeImmutable('now'))
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setStatus($status[rand(0, count($status) - 1)])
                ->setBotanist($botanists[$i % 10])
                ->setParticular($particulars[$i]);

            if ($this->hasReference(Particular::class.'_'.$i)) {
                $appointment->setParticular($this->getReference(Particular::class.'_'.$i));
            }

            $manager->persist($appointment);
            $appointments[] = $appointment;
            $this->addReference(Appointment::class.'_'.$i, $appointment);
        }

        return $appointments;
    }

    private function createAdvices(Generator $faker, ObjectManager $manager, array $status, array $botanists, array $particulars): array
    {
        $advices = [];
        for ($i = 0; $i < 10; ++$i) {
            // Advice
            $advice = new Advice();
            $advice
                ->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph)
                ->setIsPublic($faker->boolean)
                ->setCreatedAt(new \DateTimeImmutable('now'))
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setStatus($status[rand(0, count($status) - 1)])
                ->setBotanist($botanists[$i % 10])
                ->setParticular($particulars[$i]);

            if ($this->hasReference(Particular::class.'_'.$i)) {
                $advice->setParticular($this->getReference(Particular::class.'_'.$i));
            }

            $manager->persist($advice);
            $advices[] = $advice;
            $this->addReference(Advice::class.'_'.$i, $advice);
        }

        return $advices;
    }

    private function createPlants(Generator $faker, ObjectManager $manager, array $status, array $appointments, array $advices): array
    {
        $plants = [];

        for ($i = 0; $i < 20; ++$i) {
            $plant = new Plant();

            // set a botanist to a plant
            $appointment = $this->getReference(Appointment::class.'_'.rand(0, 9));
            $plant->addRequest($appointment);

            // set a advice to a plant
            $advice = $this->getReference(Advice::class.'_'.rand(0, 9));
            $plant->addRequest($advice);

            // set a particular to a plant
            if ($this->hasReference(Particular::class.'_'.$i)) {
                $plant->setParticular($this->getReference(Particular::class.'_'.$i));
            }

            $plant
                ->setName($faker->name)
                ->setDescription($faker->paragraph)
                ->setSpecies($faker->name)
                ->setCreatedAt(new \DateTimeImmutable('now'))
                ->addRequest($appointments[$i % 10])
                ->addRequest($advices[$i % 10]);

            $this->createPhoto($plant, $manager);

            $manager->persist($plant);
            $plants[] = $plant;
            $this->addReference(Plant::class.'_'.$i, $plant);
        }

        return $plants;
    }

    private function createComments(Generator $faker, ObjectManager $manager): array
    {
        $comments = [];
        for ($i = 0; $i < 20; ++$i) {
            $comment = new Comment();
            $comment
                ->setContent($faker->paragraph)
                ->setCreatedAt(new \DateTimeImmutable('now'));

            // 20 is the number of particular created
            if ($this->hasReference(Particular::class.'_'.$i % 20)) {
                $particular = $this->getReference(Particular::class.'_'.$i % 20);
                $comment->setUser($particular);
            }

            // 20 is the number of plants created
            if ($this->hasReference(Advice::class.'_'.$i % 20)) {
                $comment->setCommentAdvice($this->getReference(Advice::class.'_'.$i % 20));
            }

            $comments[] = $comment;
            $manager->persist($comment);
        }

        return $comments;
    }

    private function createPhoto(Plant $plant, ObjectManager $manager): void
    {
        $photo = new Photo();
        $photo
            ->setThumbnail('https://picsum.photos/200/300')
            ->setSmallThumbnail('https://picsum.photos/100/150')
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setPlant($plant);

        $manager->persist($photo);
    }

    private function createAddress(Generator $faker, ObjectManager $manager, Particular $particular): void
    {
        $address = new Address();
        $address
            ->setStreet($faker->streetAddress)
            ->setZipCode($faker->randomNumber(5))
            ->setCity($faker->city)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setParticular($particular);

        $manager->persist($address);
    }
}
