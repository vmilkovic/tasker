<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Faker\Factory;
use App\Entity\Workspace;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WorkspaceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        /**
         * Create workspaces
         */
        for ($workspaceNum = 1; $workspaceNum <= 10; $workspaceNum++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 5));

            $workspace = new Workspace();
            $workspace->setName('Workspace ' . $workspaceNum);
            $workspace->setDescription($faker->text(50));
            $workspace->setOwner($user);
            $workspace->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));
            $manager->persist($workspace);

            $this->addReference('workspace_' . $workspaceNum, $workspace);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
