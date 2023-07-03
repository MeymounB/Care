<?php

namespace App\DataFixtures;

use App\Entity\Botanist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class LoginFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('en_US');

        // Create a new botanist using faker
        $botanist = new Botanist();
        $botanist
            ->setEmail("botanist@verified.com")
            ->setPassword("123456789")
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setCellphone($faker->phoneNumber)
            ->setIsVerified(true);

        $manager->persist($botanist);
        $manager->flush();
    }
}
