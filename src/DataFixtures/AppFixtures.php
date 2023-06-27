<?php

namespace App\DataFixtures;

use App\Entity\Advice;
use App\Entity\Appointment;
use App\Entity\Botanist;
use App\Entity\Comment;
use App\Entity\Particular;
use App\Entity\Plant;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        // Create 10 botanists
        $botanists = [];
        for ($i = 0; $i < 10; ++$i) {
            $botanist = new Botanist();
            $botanist
                ->setEmail($faker->unique()->safeEmail)
                ->setPassword($faker->password)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setRoles(['ROLE_USER'])
                ->setCellphone($faker->phoneNumber)
                ->setIsVerified($faker->boolean);

            $manager->persist($botanist);
            $botanists[] = $botanist;
            $this->addReference(Botanist::class . '_' . $i, $botanist);
        }

        // Create 10 particular
        $particulars = [];
        for ($i = 0; $i < 10; ++$i) {
            $particular = new Particular();
            $particular
                ->setEmail($faker->unique()->safeEmail)
                ->setPassword($faker->password)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setRoles(['ROLE_USER'])
                ->setCellphone($faker->phoneNumber)
                ->setIsVerified($faker->boolean);

            $manager->persist($particular);
            $particulars[] = $particular;
            $this->addReference(Particular::class . '_' . $i, $particular);
        }

        // Create 20 plants
        $plants = [];
        for ($i = 0; $i < 20; ++$i) {
            $plant = new Plant();
            $plant
                ->setName($faker->name)
                ->setDescription($faker->paragraph)
                ->setSpecies($faker->name)
                ->setPurchasedAt(new \DateTimeImmutable('now'))
                ->setThumbnail('https://picsum.photos/200/300')
                ->setSmallThumbnail('https://picsum.photos/100/150');

            // set a particular to a plant
            if ($this->hasReference(Particular::class . '_' . $i)) {
                $plant->setParticular($this->getReference(Particular::class . '_' . $i));
            }

            $manager->persist($plant);
            $plants[] = $plant;
        }

        // Create 50 comments
        $comments = [];
        for ($i = 0; $i < 50; ++$i) {
            $comment = new Comment();
            $comment
                ->setContent($faker->paragraph)
                ->setCreatedAt(new \DateTimeImmutable('now'));

            // 20 is the number of particular created
            if ($this->hasReference(Particular::class . '_' . $i % 20)) {
                $particular = $this->getReference(Particular::class . '_' . $i % 20);
                $comment->setUser($particular);
            }

            // 20 is the number of plants created
            if ($this->hasReference(Plant::class . '_' . $i % 20)) {
                $comment->setCommentPlant($this->getReference(Plant::class . '_' . $i % 20));
            }

            $comments[] = $comment;
            $manager->persist($comment);
        }

        // Create 5 appointments
        $appointments = [];
        for ($i = 0; $i < 5; ++$i) {
            // Appointment
            $appointment = new Appointment();
            $appointment
                ->setTitle($faker->sentence)
                ->setDescription($faker->paragraph)
                ->setDate(new \DateTimeImmutable('now  ' . $i . ' days'))
                ->setType('appointment')
                ->setPlannedAt(new \DateTimeImmutable('now + ' . $i . ' days'))
                ->setIsPresential($faker->boolean)
                ->setAdress($faker->address)
                ->setLink($faker->url)
                ->setBotanist($botanist);

            if ($this->hasReference(Particular::class . '_' . $i)) {
                $appointment->setParticular($this->getReference(Particular::class . '_' . $i));
            }

            $appointments[] = $appointment;
            $manager->persist($appointment);
        }

        // Create 5 advices
        $advices = [];
        for ($i = 0; $i < 5; ++$i) {
            // Advice
            $advice = new Advice();
            $advice
                ->setTitle($faker->sentence)
                ->setDescription($faker->paragraph)
                ->setDate(new \DateTimeImmutable('now + ' . $i . ' days'))
                ->setType('advice')
                ->setIsPublic($faker->boolean)
                ->setBotanist($botanist);

            if ($this->hasReference(Particular::class . '_' . $i)) {
                $advice->setParticular($this->getReference(Particular::class . '_' . $i));
            }

            $advices[] = $advice;
            $manager->persist($advice);
        }

        // Create types of Status
        $status = [];
        foreach (['En attente', 'En cours', 'Terminé'] as $value) {
            $oneStatus = new Status();
            $oneStatus->setName($value);
            $manager->persist($oneStatus);
            $status[] = $oneStatus;
        }

        $manager->flush();
    }
}
