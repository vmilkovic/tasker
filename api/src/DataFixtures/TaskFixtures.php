<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Faker\Factory;
use App\Entity\Task;
use App\Entity\Timer;
use App\Entity\Project;
use App\Entity\Comment;
use App\Entity\Attachment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $objectManager)
    {
        $faker = Factory::create();

        $projects = $objectManager->getRepository(Project::class)->findAll();

        /**
         * All projects
         */
        foreach ($projects as $project) {
            $workflows = $project->getWorkflows();
            $projectUsers = $project->getUsers();
            $workspaceOwner = $project->getWorkspace()->getOwner();

            if (!$workflows || !$workspaceOwner) {
                continue;
            }

            /**
             * Project workflows
             */
            foreach ($workflows as $index => $workflow) {

                if ($faker->numberBetween(0, 5)) {
                    for ($taskNum = 1; $taskNum <= $faker->numberBetween(1, 10); $taskNum++) {
                        $selectedUserIndex = array_rand($projectUsers->toArray());
                        $selectedUser = $projectUsers[$selectedUserIndex] ?: $workspaceOwner;

                        /**
                         * Create task
                         */
                        $task = new Task();
                        $task->setName('Task ' . $index + 1 . $taskNum);
                        $task->setDescription($faker->text(50));
                        $task->setEstimateFrom(new DateTimeImmutable($faker->dateTimeBetween('+1 week', '+3 week')->format('Y-m-d H:i:s')));
                        $task->setEstimateTo(new DateTimeImmutable($faker->dateTimeBetween('+3 week', '+5 week')->format('Y-m-d H:i:s')));
                        $task->setCompleted($faker->boolean());
                        $task->setPosition($taskNum);
                        $task->setWorkflow($workflow);

                        /**
                         * Create timer for task
                         */
                        $timer = new Timer();
                        $timer->setTrackFrom(new DateTimeImmutable($faker->dateTimeBetween('+1 week', '+2 week')->format('Y-m-d H:i:s')));
                        $timer->setTrackTo(new DateTimeImmutable($faker->dateTimeBetween('+2 week', '+3 week')->format('Y-m-d H:i:s')));
                        $timer->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                        /**
                         * Create comment for timer
                         */
                        $timerComment = new Comment();
                        $timerComment->setText($faker->text(250));
                        $timerComment->setCommenter($selectedUser);
                        $timerComment->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                        /**
                         * Add attachment(s) to timer comment
                         */
                        if ($faker->boolean()) {
                            for ($timerCommentAttachmentNum = 1; $timerCommentAttachmentNum <= $faker->numberBetween(1, 3); $timerCommentAttachmentNum++) {
                                $timerCommentAttachment = new Attachment();
                                $timerCommentAttachment->setName('Timer Comment Attachment ' . $timerCommentAttachmentNum);
                                $timerCommentAttachment->setPath($faker->numerify('attachment-####.' . $faker->fileExtension()));
                                $timerCommentAttachment->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                                $objectManager->persist($timerCommentAttachment);
                                $timerComment->addAttachment($timerCommentAttachment);
                            }
                        }

                        $objectManager->persist($timerComment);
                        $timer->setComment($timerComment);

                        $objectManager->persist($timer);
                        $task->setTimer($timer);

                        if ($faker->numberBetween(0, 5)) {
                            $task->setAssignedTo($selectedUser);
                        }

                        $task->setCreatedBy($selectedUser);
                        $task->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                        /**
                         * Add attachment(s) to task
                         */
                        if (!$faker->numberBetween(0, 5)) {
                            for ($taskAttachmentNum = 1; $taskAttachmentNum <= $faker->numberBetween(1, 3); $taskAttachmentNum++) {

                                $taskAttachment = new Attachment();
                                $taskAttachment->setName('Task Attachment ' . $taskNum . $taskAttachmentNum);
                                $taskAttachment->setPath($faker->numerify('attachment-####.' . $faker->fileExtension()));
                                $taskAttachment->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                                $objectManager->persist($taskAttachment);
                                $task->addAttachment($taskAttachment);
                            }
                        }

                        /**
                         * Add comment(s) to task
                         */
                        if ($faker->numberBetween(0, 10)) {
                            for ($taskCommentNum = 1; $taskCommentNum <= $faker->numberBetween(1, 10); $taskCommentNum++) {
                                $commenterUserIndex = array_rand($projectUsers->toArray());
                                $commenter = $projectUsers[$commenterUserIndex] ?: $workspaceOwner;

                                $taskComment = new Comment();
                                $taskComment->setText($faker->text(250));
                                $taskComment->setCommenter($commenter);
                                $taskComment->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                                /**
                                 * Set attachment(s) for task comment
                                 */
                                if (!$faker->numberBetween(0, 10)) {

                                    for ($taskCommentAttachmentNum = 1; $taskCommentAttachmentNum <= $faker->numberBetween(1, 3); $taskCommentAttachmentNum++) {

                                        $taskCommentAttachment = new Attachment();
                                        $taskCommentAttachment->setName('Task Comment Attachment ' . $taskCommentNum . $taskCommentAttachmentNum);
                                        $taskCommentAttachment->setPath($faker->numerify('attachment-####.' . $faker->fileExtension()));
                                        $taskCommentAttachment->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                                        $objectManager->persist($taskCommentAttachment);
                                        $taskComment->addAttachment($taskCommentAttachment);
                                    }
                                }

                                $objectManager->persist($taskComment);
                                $task->addComment($taskComment);
                            }
                        }

                        $objectManager->persist($task);
                    }
                }
            }
        }

        $objectManager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            WorkspaceFixtures::class,
            ProjectFixtures::class,
            WorkflowFixtures::class
        ];
    }
}
