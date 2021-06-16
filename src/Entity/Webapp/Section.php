<?php

namespace App\Entity\Webapp;

use App\Repository\Webapp\SectionRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Section
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $attrId;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $attrName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $attrClass;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="sections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $page;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $contentType;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="sections")
     */
    private $oneArticle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $favorites = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSectionFluid = false;


    /**
     * Permet d'initialiser le slug !
     * Utilisation de slugify pour transformer une chaine de caractères en slug
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug() {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAttrId(): ?string
    {
        return $this->attrId;
    }

    public function setAttrId(string $attrId): self
    {
        $this->attrId = $attrId;

        return $this;
    }

    public function getAttrName(): ?string
    {
        return $this->attrName;
    }

    public function setAttrName(?string $attrName): self
    {
        $this->attrName = $attrName;
        return $this;
    }

    public function getAttrClass(): ?string
    {
        return $this->attrClass;
    }

    public function setAttrClass(?string $attrClass): self
    {
        $this->attrClass = $attrClass;
        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getOneArticle(): ?Article
    {
        return $this->oneArticle;
    }

    public function setOneArticle(?Article $oneArticle): self
    {
        $this->oneArticle = $oneArticle;

        return $this;
    }

    public function getFavorites(): ?bool
    {
        return $this->favorites;
    }

    public function setFavorites(bool $favorites): self
    {
        $this->favorites = $favorites;

        return $this;
    }

    public function getIsSectionFluid(): ?bool
    {
        return $this->isSectionFluid;
    }

    public function setIsSectionFluid(bool $isSectionFluid): self
    {
        $this->isSectionFluid = $isSectionFluid;

        return $this;
    }
}
