<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use App\Repository\ProjectRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
#[ApiResource]
class Project
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $estimateFrom;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $estimateTo;

    /**
     * @var Workspace
     * @ORM\ManyToOne(targetEntity="Workspace", inversedBy="projects")
     * @JoinColumn(name="workspace_id", referencedColumnName="id")
     */
    private $workspace;

    /**
     * @var User[]|ArrayCollection
     * @ManyToMany(targetEntity="User")
     * @JoinTable(
     *      name="project_users",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="project_id", referencedColumnName="id")}
     * )
     */
    private iterable $users;

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
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEstimateFrom(): ?\DateTimeImmutable
    {
        return $this->estimateFrom;
    }

    public function setEstimateFrom(?\DateTimeImmutable $estimateFrom): self
    {
        $this->estimateFrom = $estimateFrom;

        return $this;
    }

    public function getEstimateTo(): ?\DateTimeImmutable
    {
        return $this->estimateTo;
    }

    public function setEstimateTo(?\DateTimeImmutable $estimateTo): self
    {
        $this->estimateTo = $estimateTo;

        return $this;
    }

    public function getWorkspace(): Workspace
    {
        return $this->workspace;
    }

    public function setWorkspace(?Workspace $workspace)
    {
        $this->workspace = $workspace;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(User $user)
    {
        $this->users->add($user);
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
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