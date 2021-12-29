<?php

namespace App\Entity\Admin;

use App\Entity\Webapp\Page;
use App\Repository\Admin\ParameterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ParameterRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable() 
 */
class Parameter
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
    private $nameSite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sloganSite;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descrSite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOnline;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $adminEmail;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $adminWebmaster;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBlocMenuFluid = false;

    /**
     * Insertion de l'image mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="logosite_front", fileNameProperty="logoName", size="logoSize")
     * @var File|null
     */
    private $logoFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $logoName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $logoSize;

    /**
     * Insertion de l'image mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="favicon_front", fileNameProperty="faviconName", size="faviconSize")
     * @var File|null
     */
    private $faviconFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $faviconName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $faviconSize;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $offlMessage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowOfflineMessage = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowOfflineLogo = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsShowTitleSiteHome = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlFacebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlInstagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlLinkedin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sectionHome = false;

    /**
     * Insertion de l'image mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="home_front", fileNameProperty="homeName", size="homeSize")
     * @var File|null
     */
    private $homeFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $homeName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $homeSize;

    /**
     * @ORM\OneToMany(targetEntity=Page::class, mappedBy="parameter")
     */
    private $PagesFooter;

    public function __construct()
    {
        $this->PagesFooter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameSite(): ?string
    {
        return $this->nameSite;
    }

    public function setNameSite(string $nameSite): self
    {
        $this->nameSite = $nameSite;

        return $this;
    }

    public function getSloganSite(): ?string
    {
        return $this->sloganSite;
    }

    public function setSloganSite(?string $sloganSite): self
    {
        $this->sloganSite = $sloganSite;

        return $this;
    }

    public function getDescrSite(): ?string
    {
        return $this->descrSite;
    }

    public function setDescrSite(?string $descrSite): self
    {
        $this->descrSite = $descrSite;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): self
    {
        $this->isOnline = $isOnline;

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

    public function getAdminEmail(): ?string
    {
        return $this->adminEmail;
    }

    public function setAdminEmail(?string $adminEmail): self
    {
        $this->adminEmail = $adminEmail;

        return $this;
    }

    public function getAdminWebmaster(): ?string
    {
        return $this->adminWebmaster;
    }

    public function setAdminWebmaster(?string $adminWebmaster): self
    {
        $this->adminWebmaster = $adminWebmaster;

        return $this;
    }

    public function getIsBlocMenuFluid(): ?bool
    {
        return $this->isBlocMenuFluid;
    }

    public function setIsBlocMenuFluid(bool $isBlocMenuFluid): self
    {
        $this->isBlocMenuFluid = $isBlocMenuFluid;

        return $this;
    }


    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser Symfony Form),
     * assurez-vous qu'une instance de "UploadedFile" est injectée dans ce paramètre pour déclencher la mise à jour.
     * Si le paramètre de configuration 'inject_on_load' de ce bundle est défini sur 'true', ce setter doit être
     * capable d'accepter une instance de 'File' car le bundle en injectera une ici pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $logoFile
     */
    public function setLogoFile(?File $logoFile = null): void
    {
        $this->logoFile = $logoFile;

        if (null !== $logoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoName(?string $logoName): void
    {
        $this->logoName = $logoName;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoSize(?int $logoSize): void
    {
        $this->logoSize = $logoSize;
    }

    public function getLogoSize(): ?int
    {
        return $this->logoSize;
    }


    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser Symfony Form),
     * assurez-vous qu'une instance de "UploadedFile" est injectée dans ce paramètre pour déclencher la mise à jour.
     * Si le paramètre de configuration 'inject_on_load' de ce bundle est défini sur 'true', ce setter doit être
     * capable d'accepter une instance de 'File' car le bundle en injectera une ici pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $faviconFile
     */
    public function setfaviconFile(?File $faviconFile = null): void
    {
        $this->faviconFile = $faviconFile;

        if (null !== $faviconFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getfaviconFile(): ?File
    {
        return $this->faviconFile;
    }

    public function setfaviconName(?string $faviconName): void
    {
        $this->faviconName = $faviconName;
    }

    public function getfaviconName(): ?string
    {
        return $this->faviconName;
    }

    public function setfaviconSize(?int $faviconSize): void
    {
        $this->faviconSize = $faviconSize;
    }

    public function getfaviconSize(): ?int
    {
        return $this->faviconSize;
    }

    public function getOfflMessage(): ?string
    {
        return $this->offlMessage;
    }

    public function setOfflMessage(?string $offlMessage): self
    {
        $this->offlMessage = $offlMessage;

        return $this;
    }

    public function getIsShowOfflineMessage(): ?bool
    {
        return $this->isShowOfflineMessage;
    }

    public function setIsShowOfflineMessage(bool $isShowOfflineMessage): self
    {
        $this->isShowOfflineMessage = $isShowOfflineMessage;

        return $this;
    }

    public function getIsShowOfflineLogo(): ?bool
    {
        return $this->isShowOfflineLogo;
    }

    public function setIsShowOfflineLogo(bool $isShowOfflineLogo): self
    {
        $this->isShowOfflineLogo = $isShowOfflineLogo;

        return $this;
    }

    public function getIsShowTitleSiteHome(): ?bool
    {
        return $this->IsShowTitleSiteHome;
    }

    public function setIsShowTitleSiteHome(bool $IsShowTitleSiteHome): self
    {
        $this->IsShowTitleSiteHome = $IsShowTitleSiteHome;

        return $this;
    }

    public function getUrlFacebook(): ?string
    {
        return $this->urlFacebook;
    }

    public function setUrlFacebook(string $urlFacebook): self
    {
        $this->urlFacebook = $urlFacebook;

        return $this;
    }

    public function getUrlInstagram(): ?string
    {
        return $this->urlInstagram;
    }

    public function setUrlInstagram(string $urlInstagram): self
    {
        $this->urlInstagram = $urlInstagram;

        return $this;
    }

    public function getUrlLinkedin(): ?string
    {
        return $this->urlLinkedin;
    }

    public function setUrlLinkedin(string $urlLinkedin): self
    {
        $this->urlLinkedin = $urlLinkedin;

        return $this;
    }

    public function getSectionHome(): ?bool
    {
        return $this->sectionHome;
    }

    public function setSectionHome(bool $sectionHome): self
    {
        $this->sectionHome = $sectionHome;

        return $this;
    }

    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser Symfony Form),
     * assurez-vous qu'une instance de "UploadedFile" est injectée dans ce paramètre pour déclencher la mise à jour.
     * Si le paramètre de configuration 'inject_on_load' de ce bundle est défini sur 'true', ce setter doit être
     * capable d'accepter une instance de 'File' car le bundle en injectera une ici pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $homeFile
     */
    public function setHomeFile(?File $homeFile = null): void
    {
        $this->homeFile = $homeFile;

        if (null !== $homeFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getHomeFile(): ?File
    {
        return $this->homeFile;
    }

    public function setHomeName(?string $homeName): void
    {
        $this->homeName = $homeName;
    }

    public function getHomeName(): ?string
    {
        return $this->homeName;
    }

    public function setHomeSize(?int $homeSize): void
    {
        $this->homeSize = $homeSize;
    }

    public function getHomeSize(): ?int
    {
        return $this->homeSize;
    }

    /**
     * @return Collection|Page[]
     */
    public function getPagesFooter(): Collection
    {
        return $this->PagesFooter;
    }

    public function addPagesFooter(Page $pagesFooter): self
    {
        if (!$this->PagesFooter->contains($pagesFooter)) {
            $this->PagesFooter[] = $pagesFooter;
            $pagesFooter->setParameter($this);
        }

        return $this;
    }

    public function removePagesFooter(Page $pagesFooter): self
    {
        if ($this->PagesFooter->removeElement($pagesFooter)) {
            // set the owning side to null (unless already changed)
            if ($pagesFooter->getParameter() === $this) {
                $pagesFooter->setParameter(null);
            }
        }

        return $this;
    }
}
