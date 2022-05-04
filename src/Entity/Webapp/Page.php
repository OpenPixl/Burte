<?php

namespace App\Entity\Webapp;

use App\Entity\Admin\Member;
use App\Entity\Admin\Parameter;
use App\Repository\Webapp\PageRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=PageRepository::class)
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Déclare le nom de la page
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * SEO : balise Title
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $state;
    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $metaKeywords = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $tags = [];

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="pages")
     */
    private $author;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublish;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMenu;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTitle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDescription;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dispublishAt;

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
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="page", orphanRemoval=true)
     */
    private $sections;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="linkPage")
     */
    private $LinkedPage;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class)
     */
    private $parent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Parameter::class, inversedBy="PagesFooter")
     */
    private $parameter;


    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->LinkedPage = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le slug !
     * Utilisation de slugify pour transformer une chaine de caractères en slug
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug() {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->name);
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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getIsMenu(): ?bool
    {
        return $this->isMenu;
    }

    public function setIsMenu(bool $isMenu): self
    {
        $this->isMenu = $isMenu;

        return $this;
    }

    public function getMetaKeywords(): ?array
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?array $metaKeywords): self
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getAuthor(): ?Member
    {
        return $this->author;
    }

    public function setAuthor(?Member $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPublishAt(): ?\DateTimeInterface
    {
        return $this->publishAt;
    }

    public function setPublishAt(?\DateTimeInterface $publishAt): self
    {
        $this->publishAt = $publishAt;

        return $this;
    }

    public function getIsPublish(): ?bool
    {
        return $this->isPublish;
    }

    public function setIsPublish(bool $isPublish): self
    {
        $this->isPublish = $isPublish;

        return $this;
    }



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getIsTitle(): ?bool
    {
        return $this->isTitle;
    }

    public function setIsTitle(bool $isTitle): self
    {
        $this->isTitle = $isTitle;

        return $this;
    }

    public function getIsDescription(): ?bool
    {
        return $this->isDescription;
    }

    public function setIsDescription(bool $isDescription): self
    {
        $this->isDescription = $isDescription;

        return $this;
    }

    public function getDispublishAt(): ?\DateTimeInterface
    {
        return $this->dispublishAt;
    }

    public function setDispublishAt(?\DateTimeInterface $dispublishAt): self
    {
        $this->dispublishAt = $dispublishAt;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setPage($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getPage() === $this) {
                $section->setPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getLinkedPage(): Collection
    {
        return $this->LinkedPage;
    }

    public function addLinkedPage(Article $linkedPage): self
    {
        if (!$this->LinkedPage->contains($linkedPage)) {
            $this->LinkedPage[] = $linkedPage;
            $linkedPage->setLinkPage($this);
        }

        return $this;
    }

    public function removeLinkedPage(Article $linkedPage): self
    {
        if ($this->LinkedPage->removeElement($linkedPage)) {
            // set the owning side to null (unless already changed)
            if ($linkedPage->getLinkPage() === $this) {
                $linkedPage->setLinkPage(null);
            }
        }

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
        $this->createdAt = new \DateTime('now');
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
        $this->updatedAt = new \DateTime('now');
        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParameter(): ?Parameter
    {
        return $this->parameter;
    }

    public function setParameter(?Parameter $parameter): self
    {
        $this->parameter = $parameter;

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
}
