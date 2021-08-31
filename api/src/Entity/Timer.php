<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TimerRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=TimerRepository::class)
 */
#[ApiResource]
class Timer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private $uuid;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $trackFrom;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $trackTo;

    /**
     * @var Task
     * @ORM\OneToOne(targetEntity=Task::class, mappedBy="timer", cascade={"persist", "remove"})
     */
    private $task;

    /**
     * @var Issue
     * @ORM\OneToOne(targetEntity=Issue::class, mappedBy="timer", cascade={"persist", "remove"})
     */
    private $issue;

    /**
     * @var Comment
     * @ORM\OneToOne(targetEntity=Comment::class, inversedBy="timer", cascade={"persist", "remove"})
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deletedAt;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getTrackFrom(): ?\DateTimeImmutable
    {
        return $this->trackFrom;
    }

    public function setTrackFrom(?\DateTimeImmutable $trackFrom): self
    {
        $this->trackFrom = $trackFrom;

        return $this;
    }

    public function getTrackTo(): ?\DateTimeImmutable
    {
        return $this->trackTo;
    }

    public function setTrackTo(?\DateTimeImmutable $trackTo): self
    {
        $this->trackTo = $trackTo;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(Task $task): self
    {
        // set the owning side of the relation if necessary
        if ($task->getTimer() !== $this) {
            $task->setTimer($this);
        }

        $this->task = $task;

        return $this;
    }

    public function getIssue(): ?Issue
    {
        return $this->issue;
    }

    public function setIssue(Issue $issue): self
    {
        // set the owning side of the relation if necessary
        if ($issue->getTimer() !== $this) {
            $issue->setTimer($this);
        }

        $this->issue = $issue;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
