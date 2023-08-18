<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column]
    private ?int $followers = null;

    #[ORM\Column]
    private ?int $following = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Tweet::class, orphanRemoval: true)]
    private Collection $tweets;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TweetRelations::class, orphanRemoval: true)]
    private Collection $tweetRelations;

    #[ORM\OneToMany(mappedBy: 'follower', targetEntity: UserRelations::class, orphanRemoval: true)]
    private Collection $userRelations;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column]
    private ?int $qtweets = null;

    public function __construct()
    {
        $this->tweets = new ArrayCollection();
        $this->tweetRelations = new ArrayCollection();
        $this->userRelations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getFollowers(): ?int
    {
        return $this->followers;
    }

    public function setFollowers(int $followers): static
    {
        $this->followers = $followers;

        return $this;
    }

    public function getFollowing(): ?int
    {
        return $this->following;
    }

    public function setFollowing(int $following): static
    {
        $this->following = $following;

        return $this;
    }

    /**
     * @return Collection<int, Tweet>
     */
    public function getTweets(): Collection
    {
        return $this->tweets;
    }

    public function addTweet(Tweet $tweet): static
    {
        if (!$this->tweets->contains($tweet)) {
            $this->tweets->add($tweet);
            $tweet->setAuthor($this);
        }

        return $this;
    }

    public function removeTweet(Tweet $tweet): static
    {
        if ($this->tweets->removeElement($tweet)) {
            // set the owning side to null (unless already changed)
            if ($tweet->getAuthor() === $this) {
                $tweet->setAuthor(null);
            }
        }

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, TweetRelations>
     */
    public function getTweetRelations(): Collection
    {
        return $this->tweetRelations;
    }

    public function addTweetRelation(TweetRelations $tweetRelation): static
    {
        if (!$this->tweetRelations->contains($tweetRelation)) {
            $this->tweetRelations->add($tweetRelation);
            $tweetRelation->setUser($this);
        }

        return $this;
    }

    public function removeTweetRelation(TweetRelations $tweetRelation): static
    {
        if ($this->tweetRelations->removeElement($tweetRelation)) {
            // set the owning side to null (unless already changed)
            if ($tweetRelation->getUser() === $this) {
                $tweetRelation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserRelations>
     */
    public function getUserRelations(): Collection
    {
        return $this->userRelations;
    }

    public function addUserRelation(UserRelations $userRelation): static
    {
        if (!$this->userRelations->contains($userRelation)) {
            $this->userRelations->add($userRelation);
            $userRelation->setFollower($this);
        }

        return $this;
    }

    public function removeUserRelation(UserRelations $userRelation): static
    {
        if ($this->userRelations->removeElement($userRelation)) {
            // set the owning side to null (unless already changed)
            if ($userRelation->getFollower() === $this) {
                $userRelation->setFollower(null);
            }
        }

        return $this;
    }

    public function __toString(){
      return strval($this->getUsername());
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function setQtweets(int $qtweets): static
    {
        $this->qtweets = $qtweets;
        return $this;
    }

    public function getQtweets(): ?int
    {
        return $this->qtweets;
    }

    public function addQtweets(): static
    {
        $this->qtweets = $this->qtweets+1;
        return $this;
    }

    public function subQtweets(): static
    {
        $this->qtweets = $this->qtweets-1;
        return $this;
    }

    public function addFollower(): static
    {
        $this->followers = $this->followers+1;
        return $this;
    }

    public function subFollower(): static
    {
        $this->followers = $this->followers-1;
        return $this;
    }

    public function addFollowing(): static
    {
        $this->following = $this->following+1;
        return $this;
    }

    public function subFollowing(): static
    {
        $this->following = $this->following-1;
        return $this;
    }
}
