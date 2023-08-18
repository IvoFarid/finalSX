<?php

namespace App\Entity;

use App\Repository\UserRelationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRelationsRepository::class)]
class UserRelations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userRelations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $follower = null;

    #[ORM\ManyToOne(inversedBy: 'userRelations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $following = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollower(): ?User
    {
        return $this->follower;
    }

    public function setFollower(?User $follower): static
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowing(): ?User
    {
        return $this->following;
    }

    public function setFollowing(?User $following): static
    {
        $this->following = $following;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
