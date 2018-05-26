<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $index_number;

    public function getId()
    {
        return $this->id;
    }

    public function getIndexNumber(): ?int
    {
        return $this->index_number;
    }

    public function setIndexNumber(int $index_number): self
    {
        $this->index_number = $index_number;

        return $this;
    }

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="city")
     */
    private $users;

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user)
    {
        $this->users[] = $user;
    }
}
