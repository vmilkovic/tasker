<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Faker\Factory;
use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        /**
         * Create projects for workspaces
         */
        for ($projectNum = 1; $projectNum <= 20; $projectNum++) {
            $workspace = $this->getReference('workspace_' . $faker->numberBetween(1, 10));

            $project = new Project();
            $project->setName('Project ' . $projectNum);
            $project->setDescription($faker->text(50));
            $project->setEstimateFrom(new DateTimeImmutable($faker->dateTimeBetween('+1 week', '+3 week')->format('Y-m-d H:i:s')));
            $project->setEstimateTo(new DateTimeImmutable($faker->dateTimeBetween('+3 week', '+5 week')->format('Y-m-d H:i:s')));
            $project->setWorkspace($workspace);

            for ($numProjectUser = 1; $numProjectUser <= $faker->numberBetween(1, 3); $numProjectUser++) {
                $projectUser = $this->getReference('user_' . $faker->numberBetween(1, 5));
                $project->addUser($projectUser);
            }

            $project->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

            $manager->persist($project);

            $this->addReference('project_' . $projectNum, $project);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            WorkspaceFixtures::class
        ];
    }
}
