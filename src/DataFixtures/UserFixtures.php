<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordEncoder
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(locale: 'fr_FR');

        $user = new User();
        $user->setEmail('impersonator@test.fr');
        $user->setPassword($this->passwordEncoder->hashPassword(
            $user,
            'password'
        ));
        $user->setRoles(['ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH']);

        $manager->persist($user);
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword($this->passwordEncoder->hashPassword(
                $user,
                'password'
            ));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
