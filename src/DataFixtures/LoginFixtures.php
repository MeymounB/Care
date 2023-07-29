<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Botanist;
use App\Entity\Particular;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('en_US');

        // Create a new botanist using faker
        $botanist = new Botanist();
        $botanist
            ->setEmail('botanist@verified.com')
            ->setPassword($this->passwordHasher->hashPassword($botanist, '123456789'))
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setAvatar('https://picsum.photos/200/300')
            ->setCellphone($faker->phoneNumber)
            ->setIsVerified(true);

        $manager->persist($botanist);
        $manager->flush();

        $particular = new Particular();
        $particular
            ->setEmail('particular@verified.com')
            ->setPassword($this->passwordHasher->hashPassword($particular, '123456789'))
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setAvatar('https://picsum.photos/200/300')
            ->setCellphone($faker->phoneNumber)
            ->setIsVerified(true);
        $manager->persist($particular);
        $manager->flush();

        $admin = new Admin();
        $admin
            ->setEmail('admin@verified.com')
            ->setPassword($this->passwordHasher->hashPassword($admin, '123456789'))
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setAvatar('https://picsum.photos/200/300')
            ->setCellphone($faker->phoneNumber)
            ->setIsVerified(true);
        $manager->persist($admin);
        $manager->flush();
    }
}
