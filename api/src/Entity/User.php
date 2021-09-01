<?php

namespace App\Entity;

use App\Entity\Workspace;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * Tasker users
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user")
 */
#[ApiResource()]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\Type("integer")
     */
    private $id;

    /**
     * @ORM\Column(type="uuid")
     * @Assert\Uuid
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(
     *     message="Email is required"
     * )
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     * @Assert\Type(
     *     type="string",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private string $username = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(
     *     type="string",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private string $firstName = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(
     *     type="string",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private string $lastName = '';

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * * @Assert\Type(
     *     type="string",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotBlank(
     *     message="Password is required"
     * )
     */
    private string $password;

    /**
     * @ORM\Column(type="json")
     * @Assert\Type(
     *     type="array",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private array $roles = [];

    /**
     * @var Workspace[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Workspace", mappedBy="owner",  cascade={"persist"})
     * @Assert\Type(
     *     type="iterable",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private iterable $workspaces;

    /**
     * @var Project[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity=Project::class, mappedBy="users")
     * @Assert\Type(
     *     type="iterable",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private iterable $projects;

    /**
     * @var Task[]|ArrayCollection
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="createdBy")
     * @Assert\Type(
     *     type="iterable",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private iterable $createdTasks;

    /**
     * @var Task[]|ArrayCollection
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="assignedTo")
     * @Assert\Type(
     *     type="iterable",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private iterable $assignedTasks;

    /**
     * @var Issue[]|ArrayCollection
     * @ORM\OneToMany(targetEntity=Issue::class, mappedBy="createdBy")
     * @Assert\Type(
     *     type="iterable",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private iterable $createdIssues;

    /**
     * @var Issue[]|ArrayCollection
     * @ORM\OneToMany(targetEntity=Issue::class, mappedBy="assignedTo")
     * @Assert\Type(
     *     type="iterable",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private iterable $assignedIssues;

    /**
     * @var Comment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="commenter")
     * @Assert\Type(
     *     type="iterable",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\Collection(
     *     fields={
     *          "text" = {
     *              @Assert\Type("string"),
     *              @Assert\NotBlank(
     *                  message="Comment text is required"
     *              )
     *          }
     *     },
     *     allowMissingFields = true
     * )
     */
    private iterable $comments;

    /**
     * @var DateTimeImmutable A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("DateTimeInterface")
     */
    private $lastLogin;

    /**
     * @var DateTimeImmutable A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("DateTimeInterface")
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable|null A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\Type("DateTimeInterface")
     */
    private $updatedAt = null;

    /**
     * @var DateTimeImmutable|null A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\Type("DateTimeInterface")
     */
    private $deletedAt = null;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->workspaces = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->createdTasks = new ArrayCollection();
        $this->assignedTasks = new ArrayCollection();
        $this->createdIssues = new ArrayCollection();
        $this->assignedIssues = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstName(): string
    {
        return (string) $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return (string) $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Workspace[]
     */
    public function getWorkspaces()
    {
        return $this->workspaces;
    }

    public function addWorkspace(Workspace $workspace): self
    {
        if (!$this->workspace->contains($workspace)) {
            $workspace->setOwner($this);
            $this->workspaces->add($workspace);
        }

        return $this;
    }

    public function removeWorkspace(Workspace $workspace): self
    {
        if ($this->workspaces->removeElement($workspace)) {

            if ($workspace->getOwner() === $this) {
                $workspace->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->addUser($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            $project->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getCreatedTasks(): Collection
    {
        return $this->createdTasks;
    }

    public function addCreatedTask(Task $createdTask): self
    {
        if (!$this->createdTasks->contains($createdTask)) {
            $this->createdTasks[] = $createdTask;
            $createdTask->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedTask(Task $createdTask): self
    {
        if ($this->createdTasks->removeElement($createdTask)) {
            // set the owning side to null (unless already changed)
            if ($createdTask->getCreatedBy() === $this) {
                $createdTask->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getAssignedTasks(): Collection
    {
        return $this->assignedTasks;
    }

    public function addAssignedTask(Task $task): self
    {
        if (!$this->assignedTasks->contains($task)) {
            $this->assignedTasks[] = $task;
            $task->setAssignedTo($this);
        }

        return $this;
    }

    public function removeAssignedTask(Task $task): self
    {
        if ($this->assignedTasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getAssignedTo() === $this) {
                $task->setAssignedTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Issue[]
     */
    public function getCreatedIssues(): Collection
    {
        return $this->createdIssues;
    }

    public function addCreatedIssue(Issue $createdIssue): self
    {
        if (!$this->createdIssues->contains($createdIssue)) {
            $this->createdIssues[] = $createdIssue;
            $createdIssue->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedIssue(Issue $createdIssue): self
    {
        if ($this->createdIssues->removeElement($createdIssue)) {
            // set the owning side to null (unless already changed)
            if ($createdIssue->getCreatedBy() === $this) {
                $createdIssue->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Issue[]
     */
    public function getAssignedIssues(): Collection
    {
        return $this->assignedIssues;
    }

    public function addAssignedIssue(Issue $issue): self
    {
        if (!$this->assignedIssues->contains($issue)) {
            $this->assignedIssues[] = $issue;
            $issue->setAssignedTo($this);
        }

        return $this;
    }

    public function removeAssignedIssue(Issue $issue): self
    {
        if ($this->assignedIssues->removeElement($issue)) {
            // set the owning side to null (unless already changed)
            if ($issue->getAssignedTo() === $this) {
                $issue->setAssignedTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCommenter($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCommenter() === $this) {
                $comment->setCommenter(null);
            }
        }

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

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
