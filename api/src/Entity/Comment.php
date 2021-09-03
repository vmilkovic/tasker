<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\Table(name="comment")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ApiProperty(identifier=false)
     */
    private $id;

    /**
     * @ORM\Column(type="uuid")
     * @ApiProperty(identifier=true)
     * @Groups({"comment_read"})
     */
    private $uuid;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"comment_read", "comment_write"})
     */
    private $text;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @Groups({"comment_read", "comment_write"})
     */
    private $commenter; # TODO create assert for all entity types

    /**
     * @var Task
     * @ORM\ManyToOne(targetEntity=Task::class, inversedBy="comments")
     * @Groups({"comment_read", "comment_write"})
     */
    private $task;

    /**
     * @var Issue
     * @ORM\ManyToOne(targetEntity=Issue::class, inversedBy="comments")
     * @Groups({"comment_read", "comment_write"})
     */
    private $issue;

    /**
     * @var Timer
     * @ORM\OneToOne(targetEntity=Timer::class, mappedBy="comment", cascade={"persist", "remove"})
     * @Groups({"comment_read", "comment_write"})
     */
    private $timer;

    /**
     * @var Attachment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="comment")
     * @ApiSubresource(maxDepth=1)
     * @Groups({"comment_read", "comment_write"})
     */
    private iterable $attachments;

    /**
     * @var DateTimeImmutable|null A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("DateTimeInterface")
     * @Groups({"comment_read", "comment_create"})
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable|null A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\Type(type={"DateTimeInterface, null"})
     * @Groups({"comment_read", "comment_update"})
     */
    private $updatedAt;

    /**
     * @var DateTimeImmutable|null A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\Type(type={"DateTimeInterface, null"})
     * @Groups({"comment_read"})
     */
    private $deletedAt;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCommenter(): ?User
    {
        return $this->commenter;
    }

    public function setCommenter(?User $commenter): self
    {
        # TODO set commenter depending of timer-task, task or issue user

        $this->commenter = $commenter;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getIssue(): ?Issue
    {
        return $this->issue;
    }

    public function setIssue(?Issue $issue): self
    {
        $this->issue = $issue;

        return $this;
    }

    public function getTimer(): ?Timer
    {
        return $this->timer;
    }

    public function setTimer(?Timer $timer): self
    {
        // unset the owning side of the relation if necessary
        if ($timer === null && $this->timer !== null) {
            $this->timer->setComment(null);
        }

        // set the owning side of the relation if necessary
        if ($timer !== null && $timer->getComment() !== $this) {
            $timer->setComment($this);
        }

        $this->timer = $timer;

        return $this;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setComment($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getComment() === $this) {
                $attachment->setComment(null);
            }
        }

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
