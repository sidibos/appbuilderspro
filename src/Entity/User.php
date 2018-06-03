<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $salt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserDetails", mappedBy="user", cascade={"persist", "remove"})
     */
    private $userDetails;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="owner")
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quote", mappedBy="owner")
     */
    private $owner;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->owner = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }
    
    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }

    public function setUserDetails(UserDetails $userDetails): self
    {
        $this->userDetails = $userDetails;

        // set the owning side of the relation if necessary
        if ($this !== $userDetails->getUser()) {
            $userDetails->setUser($this);
        }

        return $this;
    }

    public function __toString()
    {
        return strval( $this->getId() );
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
            $project->setOwner($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getOwner() === $this) {
                $project->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Quote[]
     */
    public function getOwner(): Collection
    {
        return $this->owner;
    }

    public function addOwner(Quote $owner): self
    {
        if (!$this->owner->contains($owner)) {
            $this->owner[] = $owner;
            $owner->setOwner($this);
        }

        return $this;
    }

    public function removeOwner(Quote $owner): self
    {
        if ($this->owner->contains($owner)) {
            $this->owner->removeElement($owner);
            // set the owning side to null (unless already changed)
            if ($owner->getOwner() === $this) {
                $owner->setOwner(null);
            }
        }

        return $this;
    }
}
