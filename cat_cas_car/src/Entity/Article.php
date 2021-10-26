<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $slug;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;
    
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $publishedAt;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $author;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $likeCount;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFilename;
    
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
    
    public function getAuthor(): ?string
    {
        return $this->author;
    }
    
    public function setAuthor(?string $author): self
    {
        $this->author = $author;
        
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
    
    public function getAuthorAvatarPath(): string
    {
        return 'https://robohash.org/'. $this->getAuthor() .'?set=set4';
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
}
