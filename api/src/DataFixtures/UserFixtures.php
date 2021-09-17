<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {

        /**
         * Crete main user
         */
        $mainUser = $manager->getRepository(User::class)->findOneBy(['username' => 'vmilkovic']);

        if (!$mainUser) {
            $mainUser = new User();
            $mainUser->setEmail('vedran@milkovic.dev');
            $mainUser->setUsername('vmilkovic');
            $mainUser->setFirstName('Vedran');
            $mainUser->setLastName('Milković');
            $mainUser->setPassword($this->passwordHasher->hashPassword($mainUser, '12345'));
            $mainUser->setCreatedAt(new DateTimeImmutable());
            $mainUser->setLastLogin(new DateTimeImmutable());

            $manager->persist($mainUser);
        }

        $faker = Factory::create();

        /**
         * Create users
         */
        for ($userNum = 1; $userNum <= 5; $userNum++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setUsername($faker->name());
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setPassword($this->passwordHasher->hashPassword($user, '12345'));
            $user->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));
            $user->setLastLogin(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));
            $manager->persist($user);

            $this->addReference('user_' . $userNum, $user);
        }

        $manager->flush();
    }
}
