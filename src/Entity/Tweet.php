<?php

namespace App\Entity;

use App\Repository\TweetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $likes = null;

    #[ORM\Column]
    private ?int $retweets = null;

    #[ORM\Column]
    private ?int $cites = null;

    #[ORM\ManyToOne(inversedBy: 'tweets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'tweet', targetEntity: TweetRelations::class, orphanRemoval: true)]
    private Collection $tweetRelations;

    #[ORM\Column]
    private ?int $saved = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'tweets')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $tweets;

    #[ORM\Column]
    private ?int $comments = null;

    public function __construct()
    {
        $this->tweetRelations = new ArrayCollection();
        // $this->parentTweet = new ArrayCollection();
        $this->tweets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): static
    {
        $this->likes = $likes;

        return $this;
    }

    public function addLike(): static
    {
        $this->likes = $this->likes+1;
        return $this;
    }

    public function subLike(): static
    {
        $this->likes = $this->likes-1;
        return $this;
    }

    public function subRt(): static
    {
        $this->retweets = $this->retweets-1;
        return $this;
    }

    public function getRetweets(): ?int
    {
        return $this->retweets;
    }

    public function addRtw(): static
    {
        $this->retweets = $this->retweets+1;
        return $this;
    }

    public function setRetweets(int $retweets): static
    {
        $this->retweets = $retweets;

        return $this;
    }

    public function getCites(): ?int
    {
        return $this->cites;
    }

    public function addCite(): static
    {
        $this->cites = $this->cites+1;
        return $this;
    }

    public function subCite(): static
    {
        $this->cites = $this->cites-1;
        return $this;
    }

    public function setCites(int $cites): static
    {
        $this->cites = $cites;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

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
            $tweetRelation->setTweet($this);
        }

        return $this;
    }

    public function removeTweetRelation(TweetRelations $tweetRelation): static
    {
        if ($this->tweetRelations->removeElement($tweetRelation)) {
            // set the owning side to null (unless already changed)
            if ($tweetRelation->getTweet() === $this) {
                $tweetRelation->setTweet(null);
            }
        }

        return $this;
    }

    public function getSaved(): ?int
    {
        return $this->saved;
    }

    public function addSave(): static
    {
        $this->saved = $this->saved+1;
        return $this;
    }
    
    public function subSave(): static
    {
        $this->saved = $this->saved-1;
        return $this;
    }

    public function setSaved(int $saved): static
    {
        $this->saved = $saved;
        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTweets(): Collection
    {
        return $this->tweets;
    }

    public function addTweet(self $tweet): static
    {
        if (!$this->tweets->contains($tweet)) {
            $this->tweets->add($tweet);
            $tweet->setParent($this);
        }

        return $this;
    }

    public function removeTweet(self $tweet): static
    {
        if ($this->tweets->removeElement($tweet)) {
            // set the owning side to null (unless already changed)
            if ($tweet->getParent() === $this) {
                $tweet->setParent(null);
            }
        }

        return $this;
    }

    public function getComments(): ?int
    {
        return $this->comments;
    }

    public function setComments(int $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function addComment(): static
    {
        $this->comments = $this->comments+1;
        return $this;
    }
    
    public function subComment(): static
    {
        $this->comments = $this->comments-1;
        return $this;
    }
}
