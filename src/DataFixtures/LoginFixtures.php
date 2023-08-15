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
    private UserPasswordHasherInterface $passwordHasher;

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
            ->setPassword($this->passwordHasher->hashPassword($botanist, 'safeP@ssword123'))
            ->setFirstName('botanist')
            ->setLastName('verified')
            ->setAvatar('https://picsum.photos/200/300')
            ->setCellphone($faker->phoneNumber)
            ->setIsVerified(true);

        $manager->persist($botanist);
        $manager->flush();

        $particular = new Particular();
        $particular
            ->setEmail('particular@verified.com')
            ->setPassword($this->passwordHasher->hashPassword($particular, 'safeP@ssword123'))
            ->setFirstName('particular')
            ->setLastName('verified')
            ->setAvatar('https://picsum.photos/200/300')
            ->setCellphone($faker->phoneNumber)
            ->setIsVerified(true);
        $manager->persist($particular);
        $manager->flush();

        $admin = new Admin();
        $admin
            ->setEmail('admin@verified.com')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'safeP@ssword123'))
            ->setFirstName('admin')
            ->setLastName('verified')
            ->setAvatar('https://picsum.photos/200/300')
            ->setCellphone($faker->phoneNumber)
            ->setIsVerified(true);
        $manager->persist($admin);
        $manager->flush();
    }
}
