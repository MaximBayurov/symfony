<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @Assert\EnableAutoMapping()
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
     * @Assert\NotBlank(message="У вашей статьи должен быть заголовок!")
     * @Assert\Length(min="3", minMessage="Название статьи должно быть минимум 3 символа в длину!")
     */
    #[Groups(["main"])]
    private $title;
    
    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\DisableAutoMapping()
     */
    #[Groups(["main"])]
    private $slug;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="Обязательное поле!")
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Обязательное поле!")
     */
    #[Groups(["main"])]
    private $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="100", maxMessage="Описание статьи должно быть не длинее 100 символов!")
     */
    private $description;
    
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Assert\DisableAutoMapping()
     */
    protected $createdAt;
    
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Assert\DisableAutoMapping()
     */
    protected $updatedAt;

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
    
    public function setTitle(?string $title): self
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
    
    public function isPublished(): bool
    {
        return $this->getPublishedAt() !== null;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    /**
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (mb_stripos($this->getTitle(), 'собак') !== false) {
            $context->buildViolation('Про собак писать запрещено!')
                ->atPath('title')
                ->addViolation();
        }
        
        if (preg_match_all('/\d+/',$this->getTitle()) > 0) {
            $context->buildViolation('Нельзя использовать цифры в названии статьи!')
                ->atPath('title')
                ->addViolation();
        }
    }
}
