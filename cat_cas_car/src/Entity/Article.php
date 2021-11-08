<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    use TimestampableEntity;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["main"])]
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["main"])]
    private $title;
    
    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=100, unique=true)
     */
    #[Groups(["main"])]
    private $slug;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Groups(["main"])]
    private $body;
    
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[Groups(["main"])]
    private $publishedAt;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[Groups(["main"])]
    private $likeCount;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["main"])]
    private $imageFilename;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    #[Groups(["main"])]
    private $keywords = [];

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    #[Groups(["main"])]
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="articles")
     */
    #[Groups(["main"])]
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["main"])]
    private $author;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): self
    {
        $this->title = $title;
        
        return $this;
    }
    
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    public function getBody(): ?string
    {
        return $this->body;
    }
    
    public function setBody(?string $body): self
    {
        $this->body = $body;
        
        return $this;
    }
    
    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }
    
    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        
        return $this;
    }
    
    public function getLikeCount(): ?int
    {
        return $this->likeCount;
    }
    
    public function setLikeCount(?int $likeCount): self
    {
        $this->likeCount = $likeCount;
        
        return $this;
    }
    
    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }
    
    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;
        
        return $this;
    }
    
    public function getImagePath(): string
    {
        return '/images/' . $this->getImageFilename();
    }
    
    public function like(): self
    {
        $this->likeCount++;
        return $this;
    }
    
    public function dislike(): self
    {
        $this->likeCount--;
        return $this;
    }

    public function getKeywords(): ?array
    {
        return $this->keywords;
    }

    public function setKeywords(?array $keywords): self
    {
        $this->keywords = $keywords;

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
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getAuthor(): ?user
    {
        return $this->author;
    }

    public function setAuthor(?user $author): self
    {
        $this->author = $author;

        return $this;
    }
}
