<?php

namespace App\DataFixtures;

use App\Entity\Advice;
use App\Entity\Appointment;
use App\Entity\Botanist;
use App\Entity\Comment;
use App\Entity\Particular;
use App\Entity\Plant;
use App\Entity\Status;
use App\Entity\User;
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
            $botanist->setEmail($faker->email);
            $botanist->setPassword($faker->password);
            $botanist->setFirstName($faker->firstName);
            $botanist->setLastName($faker->lastName);
            $botanist->setIsVerified($faker->boolean);
            $manager->persist($botanist);
            $botanists[] = $botanist;
        }

        // Create 10 particular
        $particulars = [];
        for ($i = 0; $i < 10; ++$i) {
            $particular = new Particular();
            $particular->setEmail($faker->unique()->safeEmail);
            // $particular->setEmail($faker->email);
            $particular->setPassword($faker->password);
            $particular->setFirstName($faker->firstName);
            $particular->setLastName($faker->lastName);
            $particular->setIsVerified($faker->boolean);

            $this->addReference(Particular::class.'_'.$i, $particular);
            $manager->persist($particular);
            $particulars[] = $particular;
        }

        // Create 20 plants
        $plants = [];
        for ($i = 0; $i < 20; ++$i) {
            $plant = new Plant();
            $plant
                ->setName($faker->name)
                ->setSpecies($faker->name)
                ->setThumbnail('https://picsum.photos/200/300')
                ->setSmallThumbnail('https://picsum.photos/100/150');

            // set the particular for this plant, assuming you have loaded ParticularFixtures before
            if ($this->hasReference(Particular::class.'_'.$i)) {
                $plant->setParticular($this->getReference(Particular::class.'_'.$i));
            }

            $manager->persist($plant);
            $plants[] = $plant;
        }

        // Create 50 comments
        $comments = [];
        for ($i = 0; $i < 50; ++$i) {
            $comment = new Comment();
            $comment->setContent($faker->paragraph);
            $comment->setCreatedAt(new \DateTimeImmutable('now'));

            // Reference must be run before persisting the object.
            // 20 is the number of users created in UserFixtures.
            if ($this->hasReference(User::class.'_'.$i % 20)) {
                $comment->setUser($this->getReference(User::class.'_'.$i % 20));
            }

            // Reference must be run before persisting the object.
            // 20 is the number of plants created in PlantFixtures.
            if ($this->hasReference(Plant::class.'_'.$i % 20)) {
                $comment->setCommentPlant($this->getReference(Plant::class.'_'.$i % 20));
            }

            $comments[] = $comment;
            $manager->persist($comment);
        }

        // Create 5 appointments
        $appointments = [];
        for ($i = 0; $i < 5; ++$i) {
            // Appointment
            $appointment = new Appointment();
            $appointment->setTitle($faker->sentence);
            $appointment->setDescription($faker->paragraph);
            $appointment->setDate(new \DateTime('now + '.$i.' days'));
            $appointment->setType('appointment');
            // $appointment->setCreatedAt(new \DateTimeImmutable('now'));
            // $appointment->setUpdatedAt(new \DateTime('now'));
            $appointment->setIsPresential($faker->boolean);
            $appointment->setAdress($faker->address);
            $appointment->setLink($faker->url);
            $appointment->setBotanist($botanist);

            if ($this->hasReference(Particular::class.'_'.$i)) {
                $appointment->setParticular($this->getReference(Particular::class.'_'.$i));
            }
            $appointments[] = $appointment;
            $manager->persist($appointment);
        }

        // Create 5 advices
        $advices = [];
        for ($i = 0; $i < 5; ++$i) {
            // Advice
            $advice = new Advice();
            $advice->setTitle($faker->sentence);
            $advice->setDescription($faker->paragraph);
            $advice->setDate(new \DateTime('now + '.$i.' days'));
            $advice->setType('advice');
            // $advice->setCreatedAt(new \DateTimeImmutable('now'));
            // $advice->setUpdatedAt(new \DateTime('now'));
            $advice->setIsPublic($faker->boolean);
            $advice->setBotanist($botanist);

            if ($this->hasReference(Particular::class.'_'.$i)) {
                $advice->setParticular($this->getReference(Particular::class.'_'.$i));
            }

            $advices[] = $advice;
            $manager->persist($advice);
        }

        // Create type of Status
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
