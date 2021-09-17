<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Faker\Factory;
use App\Entity\Task;
use App\Entity\Issue;
use App\Entity\Timer;
use App\Entity\Comment;
use App\Entity\Attachment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class IssueFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $objectManager)
    {
        $faker = Factory::create();

        $tasks = $objectManager->getRepository(Task::class)->findAll();

        foreach ($tasks as $task) {
            $assignedTaskUser = $task->getAssignedTo();
            $projectUsers = $task->getWorkflow()->getProject()->getUsers();
            $selectedUserIndex = array_rand($projectUsers->toArray());
            $selectedUser = $projectUsers[$selectedUserIndex] ?: $assignedTaskUser;

            /**
             * Create issue if task has assigned user
             */
            if (!$faker->numberBetween(0, 5) && $assignedTaskUser) {

                for ($issueNum = 1; $issueNum <= $faker->numberBetween(1, 3); $issueNum++) {

                    /**
                     * Create issue
                     */
                    $issue = new Issue();
                    $issue->setName('Issue ' . $issueNum);
                    $issue->setDescription($faker->text(50));
                    $issue->setEstimateFrom(new DateTimeImmutable($faker->dateTimeBetween('+1 week', '+3 week')->format('Y-m-d H:i:s')));
                    $issue->setEstimateTo(new DateTimeImmutable($faker->dateTimeBetween('+3 week', '+5 week')->format('Y-m-d H:i:s')));
                    $issue->setIsResolved($faker->boolean());

                    /**
                     * Create timer for issue
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
                            $timerCommentAttachment->setName('Attachment ' . $timerCommentAttachmentNum);
                            $timerCommentAttachment->setPath($faker->numerify('attachment-####.' . $faker->fileExtension()));
                            $timerCommentAttachment->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                            $objectManager->persist($timerCommentAttachment);
                            $timerComment->addAttachment($timerCommentAttachment);
                        }
                    }

                    $objectManager->persist($timerComment);
                    $timer->setComment($timerComment);

                    $objectManager->persist($timer);
                    $issue->setTimer($timer);

                    if ($faker->numberBetween(0, 5)) {
                        $issue->setAssignedTo($selectedUser);
                    }

                    $issue->setCreatedBy($assignedTaskUser);
                    $issue->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                    /**
                     * Add attachment(s) to issue
                     */
                    if (!$faker->numberBetween(0, 5)) {
                        for ($issueAttachmentNum = 1; $issueAttachmentNum <= $faker->numberBetween(1, 3); $issueAttachmentNum++) {
                            $issueAttachment = new Attachment();
                            $issueAttachment->setName('Issue Attachment ' . $issueNum . $issueAttachmentNum);
                            $issueAttachment->setPath($faker->numerify('attachment-####.' . $faker->fileExtension()));
                            $issueAttachment->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                            $objectManager->persist($issueAttachment);
                            $issue->addAttachment($issueAttachment);
                        }
                    }

                    /**
                     * Add comment(s) to issue
                     */
                    if ($faker->numberBetween(0, 10)) {
                        for ($issueCommentNum = 1; $issueCommentNum <= $faker->numberBetween(1, 10); $issueCommentNum++) {
                            $commenterUserIndex = array_rand($projectUsers->toArray());
                            $commenter = $projectUsers[$commenterUserIndex] ?: $assignedTaskUser;

                            $issueComment = new Comment();
                            $issueComment->setText($faker->text(250));
                            $issueComment->setCommenter($commenter);
                            $issueComment->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                            /**
                             * Set attachment(s) for issue comment
                             */
                            if (!$faker->numberBetween(0, 10)) {

                                for ($issueCommentAttachmentNum = 1; $issueCommentAttachmentNum <= $faker->numberBetween(1, 3); $issueCommentAttachmentNum++) {

                                    $issueCommentAttachment = new Attachment();
                                    $issueCommentAttachment->setName('Issue Comment Attachment ' . $issueCommentNum . $issueCommentAttachmentNum);
                                    $issueCommentAttachment->setPath($faker->numerify('attachment-####.' . $faker->fileExtension()));
                                    $issueCommentAttachment->setCreatedAt(new DateTimeImmutable($faker->dateTime()->format('Y-m-d H:i:s')));

                                    $objectManager->persist($issueCommentAttachment);
                                    $issueComment->addAttachment($issueCommentAttachment);
                                }
                            }

                            $objectManager->persist($issueComment);
                            $issue->addComment($issueComment);
                        }
                    }

                    $objectManager->persist($issue);
                    $task->addIssue($issue);
                }

                $objectManager->persist($task);
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
            WorkflowFixtures::class,
            TaskFixtures::class
        ];
    }
}
