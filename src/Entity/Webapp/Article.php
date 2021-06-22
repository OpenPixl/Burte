<?php

namespace App\Entity\Webapp;

use App\Entity\Admin\Member;
use App\Repository\Webapp\ArticleRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable()
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
     * @ORM\Column(type="string", length=120)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * Insertion de l'image mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="articles_front", fileNameProperty="articleFrontName", size="articleFrontSize")
     * @var File|null
     */
    private $articleFrontFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $articleFrontName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $articleFrontSize;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="oneArticle")
     */
    private $sections;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Author;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $state;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReadMore = false;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $articleFrontPosition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArticleFrontFluid = false;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     */
    private $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->sections = new ArrayCollection();
    }

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }


    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser Symfony Form),
     * assurez-vous qu'une instance de "UploadedFile" est injectée dans ce paramètre pour déclencher la mise à jour.
     * Si le paramètre de configuration 'inject_on_load' de ce bundle est défini sur 'true', ce setter doit être
     * capable d'accepter une instance de 'File' car le bundle en injectera une ici pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $articleFrontFile
     */
    public function setArticleFrontFile(?File $articleFrontFile = null): void
    {
        $this->articleFrontFile = $articleFrontFile;

        if (null !== $articleFrontFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getArticleFrontFile(): ?File
    {
        return $this->articleFrontFile;
    }

    public function setArticleFrontName(?string $articleFrontName): void
    {
        $this->articleFrontName = $articleFrontName;
    }

    public function getArticleFrontName(): ?string
    {
        return $this->articleFrontName;
    }

    public function setArticleFrontSize(?int $articleFrontSize): void
    {
        $this->articleFrontSize = $articleFrontSize;
    }

    public function getArticleFrontSize(): ?int
    {
        return $this->articleFrontSize;
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
            $section->setOneArticle($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getOneArticle() === $this) {
                $section->setOneArticle(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getAuthor(): ?Member
    {
        return $this->Author;
    }

    public function setAuthor(?Member $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getIsReadMore(): ?bool
    {
        return $this->isReadMore;
    }

    public function setIsReadMore(bool $isReadMore): self
    {
        $this->isReadMore = $isReadMore;

        return $this;
    }

    public function getArticleFrontPosition(): ?string
    {
        return $this->articleFrontPosition;
    }

    public function setArticleFrontPosition(string $articleFrontPosition): self
    {
        $this->articleFrontPosition = $articleFrontPosition;

        return $this;
    }

    public function getIsArticleFrontFluid(): ?bool
    {
        return $this->isArticleFrontFluid;
    }

    public function setIsArticleFrontFluid(bool $isArticleFrontFluid): self
    {
        $this->isArticleFrontFluid = $isArticleFrontFluid;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
