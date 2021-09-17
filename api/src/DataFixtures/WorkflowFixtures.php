<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Faker\Factory;
use App\Entity\Workflow;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WorkflowFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $projects = range(1, 20);

        /**
         * Create workflows for projects
         */
        foreach ($projects as $projectNum) {
            $project = $this->getReference('project_' . $projectNum);

            if ($faker->numberBetween(0, 10)) {
                for ($workflowNum = 1; $workflowNum <= $faker->numberBetween(1, 5); $workflowNum++) {
                    $workflow = new Workflow();
                    $workflow->setName('Workflow ' . $workflowNum);
                    $workflow->setDescription($faker->text(50));
                    $workflow->setPosition($workflowNum);
                    $workflow->setProject($project);
                    $workflow->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));
                    $manager->persist($workflow);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            WorkspaceFixtures::class,
            ProjectFixtures::class
        ];
    }
}
