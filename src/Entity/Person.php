<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'peopleWorkingOnIt')]
    private Collection $currentProjects;

    /**
     * @var Collection<int, Assignment>
     */
    #[ORM\OneToMany(targetEntity: Assignment::class, mappedBy: 'person', orphanRemoval: true)]
    private Collection $assignments;

    public function __construct()
    {
        $this->currentProjects = new ArrayCollection();
        $this->assignments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getCurrentProjects(): Collection
    {
        return $this->currentProjects;
    }

    public function addCurrentProject(Project $currentProject): static
    {
        if (!$this->currentProjects->contains($currentProject)) {
            $this->currentProjects->add($currentProject);
            $currentProject->addPeopleWorkingOnIt($this);
        }

        return $this;
    }

    public function removeCurrentProject(Project $currentProject): static
    {
        if ($this->currentProjects->removeElement($currentProject)) {
            $currentProject->removePeopleWorkingOnIt($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Assignment>
     */
    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assignment): static
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments->add($assignment);
            $assignment->setPerson($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): static
    {
        if ($this->assignments->removeElement($assignment)) {
            // set the owning side to null (unless already changed)
            if ($assignment->getPerson() === $this) {
                $assignment->setPerson(null);
            }
        }

        return $this;
    }
}
