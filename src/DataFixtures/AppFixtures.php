<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
//        $user->setEmail('admin1@gmail.com');
        $user->setEmail('admin@gmail.com');

//        $user->setPassword("adminadmin");
        $user->setName('admin');
        $user->setRole(["ROLE_ADMIN"]);

        $hashedPassword = $this->hasher->hashPassword($user, 6789);
        $user->setPassword($hashedPassword);


        $manager->persist($user);
        $manager->flush();
    }
}
