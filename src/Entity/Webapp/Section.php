<?php

namespace App\Entity\Webapp;

use App\Entity\Gestapp\ProductCategory;
use App\Repository\Webapp\SectionRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable()
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowtitle = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowdescription = false;

    /**
     * Insertion de l'image mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="logoStructuresite_front", fileNameProperty="cssBackgroundImageName", size="cssBackgroundImageSize")
     * @var File|null
     */
    private $cssBackgroundImageFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $cssBackgroundImageName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $cssBackgroundImageSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $positionfavorite;

    /**
     * @ORM\ManyToOne(targetEntity=ProductCategory::class, inversedBy="sections")
     */
    private $OneCatproduct;


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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getIsShowtitle(): ?bool
    {
        return $this->isShowtitle;
    }

    public function setIsShowtitle(bool $isShowtitle): self
    {
        $this->isShowtitle = $isShowtitle;

        return $this;
    }

    public function getIsShowdescription(): ?bool
    {
        return $this->isShowdescription;
    }

    public function setIsShowdescription(bool $isShowdescription): self
    {
        $this->isShowdescription = $isShowdescription;

        return $this;
    }

    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser Symfony Form),
     * assurez-vous qu'une instance de "UploadedFile" est injectée dans ce paramètre pour déclencher la mise à jour.
     * Si le paramètre de configuration 'inject_on_load' de ce bundle est défini sur 'true', ce setter doit être
     * capable d'accepter une instance de 'File' car le bundle en injectera une ici pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $cssBackgroundImageFile
     */
    public function setCssBackgroundImageFile(?File $cssBackgroundImageFile = null): void
    {
        $this->cssBackgroundImageFile = $cssBackgroundImageFile;

        if (null !== $cssBackgroundImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCssBackgroundImageFile(): ?File
    {
        return $this->cssBackgroundImageFile;
    }

    public function setCssBackgroundImageName(?string $cssBackgroundImageName): void
    {
        $this->cssBackgroundImageName = $cssBackgroundImageName;
    }

    public function getCssBackgroundImageName(): ?string
    {
        return $this->cssBackgroundImageName;
    }

    public function setCssBackgroundImageSize(?int $cssBackgroundImageSize): void
    {
        $this->cssBackgroundImageSize = $cssBackgroundImageSize;
    }

    public function getCssBackgroundImageSize(): ?int
    {
        return $this->cssBackgroundImageSize;
    }

    public function getPositionfavorite(): ?int
    {
        return $this->positionfavorite;
    }

    public function setPositionfavorite(int $positionfavorite): self
    {
        $this->positionfavorite = $positionfavorite;

        return $this;
    }

    public function getOneCatproduct(): ?ProductCategory
    {
        return $this->OneCatproduct;
    }

    public function setOneCatproduct(?ProductCategory $OneCatproduct): self
    {
        $this->OneCatproduct = $OneCatproduct;

        return $this;
    }
}
