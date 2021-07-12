<?php

namespace App\Entity\Admin;

use App\Entity\Webapp\illustrationOne;
use App\Repository\Admin\StructureRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Ignore;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=StructureRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable()
 */
class Structure
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
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $respFirstName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $respLastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complement;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $ape;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tvaNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlWeb;

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
     * @ORM\Column(type="string", length=255)
     */
    private $EmailStruct;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $phoneDesk;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $phoneGsm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $FirstActivity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $secondActivity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $projectDev;

    /**
     * Insertion de l'logoStructure mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="articles_front", fileNameProperty="logoStructureName", size="logoStructureSize")
     * @Ignore()
     * @var File|null
     */
    private $logoStructureFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $logoStructureName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $logoStructureSize;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Member::class, mappedBy="structure")
     */
    private $members;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    
    /**
     * Insertion de l'image mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="illustration_structure", fileNameProperty="illustrationName", size="illustrationSize")
     * @var File|null
     */
    private $illustrationFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $illustrationName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $illustrationSize;


    /**
     * Insertion de l'image mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="illustration_structure", fileNameProperty="illustrationtwoName", size="illustrationtwoSize")
     * @var File|null
     */
    private $illustrationtwoFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $illustrationtwoName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $illustrationtwoSize;


    /**
     * Insertion de l'image mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="illustration_structure", fileNameProperty="illustrationthirdName", size="illustrationthirdSize")
     * @var File|null
     */
    private $illustrationthirdFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $illustrationthirdName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $illustrationthirdSize;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $jaf;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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
            $this->slug = $slugify->slugify($this->name);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getRespFirstName(): ?string
    {
        return $this->respFirstName;
    }

    public function setRespFirstName(string $respFirstName): self
    {
        $this->respFirstName = $respFirstName;

        return $this;
    }

    public function getRespLastName(): ?string
    {
        return $this->respLastName;
    }

    public function setRespLastName(string $respLastName): self
    {
        $this->respLastName = $respLastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getApe(): ?string
    {
        return $this->ape;
    }

    public function setApe(?string $ape): self
    {
        $this->ape = $ape;

        return $this;
    }

    public function getTvaNumber(): ?string
    {
        return $this->tvaNumber;
    }

    public function setTvaNumber(?string $tvaNumber): self
    {
        $this->tvaNumber = $tvaNumber;

        return $this;
    }

    public function getUrlWeb(): ?string
    {
        return $this->urlWeb;
    }

    public function setUrlWeb(?string $urlWeb): self
    {
        $this->urlWeb = $urlWeb;

        return $this;
    }

    public function getUrlFacebook(): ?string
    {
        return $this->urlFacebook;
    }

    public function setUrlFacebook(?string $urlFacebook): self
    {
        $this->urlFacebook = $urlFacebook;

        return $this;
    }

    public function getUrlInstagram(): ?string
    {
        return $this->urlInstagram;
    }

    public function setUrlInstagram(?string $urlInstagram): self
    {
        $this->urlInstagram = $urlInstagram;

        return $this;
    }

    public function getUrlLinkedin(): ?string
    {
        return $this->urlLinkedin;
    }

    public function setUrlLinkedin(?string $urlLinkedin): self
    {
        $this->urlLinkedin = $urlLinkedin;

        return $this;
    }

    public function getEmailStruct(): ?string
    {
        return $this->EmailStruct;
    }

    public function setEmailStruct(string $EmailStruct): self
    {
        $this->EmailStruct = $EmailStruct;

        return $this;
    }

    public function getPhoneDesk(): ?string
    {
        return $this->phoneDesk;
    }

    public function setPhoneDesk(?string $phoneDesk): self
    {
        $this->phoneDesk = $phoneDesk;

        return $this;
    }

    public function getPhoneGsm(): ?string
    {
        return $this->phoneGsm;
    }

    public function setPhoneGsm(?string $phoneGsm): self
    {
        $this->phoneGsm = $phoneGsm;

        return $this;
    }

    public function getFirstActivity(): ?string
    {
        return $this->FirstActivity;
    }

    public function setFirstActivity(?string $FirstActivity): self
    {
        $this->FirstActivity = $FirstActivity;

        return $this;
    }

    public function getSecondActivity(): ?string
    {
        return $this->secondActivity;
    }

    public function setSecondActivity(?string $secondActivity): self
    {
        $this->secondActivity = $secondActivity;

        return $this;
    }

    public function getProjectDev(): ?string
    {
        return $this->projectDev;
    }

    public function setProjectDev(?string $projectDev): self
    {
        $this->projectDev = $projectDev;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $logoStructureFile
     */
    public function setLogoStructureFile(?File $logoStructureFile = null): void
    {
        $this->logoStructureFile = $logoStructureFile;

        if (null !== $logoStructureFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getLogoStructureFile(): ?File
    {
        return $this->logoStructureFile;
    }

    public function setLogoStructureName(?string $logoStructureName): void
    {
        $this->logoStructureName = $logoStructureName;
    }

    public function getLogoStructureName(): ?string
    {
        return $this->logoStructureName;
    }

    public function setLogoStructureSize(?int $logoStructureSize): void
    {
        $this->logoStructureSize = $logoStructureSize;
    }

    public function getLogoStructureSize(): ?int
    {
        return $this->logoStructureSize;
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

    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setStructure($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getStructure() === $this) {
                $member->setStructure(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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

    public function getJaf(): ?string
    {
        return $this->jaf;
    }

    public function setJaf(?string $jaf): self
    {
        $this->jaf = $jaf;

        return $this;
    }

    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser Symfony Form),
     * assurez-vous qu'une instance de "UploadedFile" est injectée dans ce paramètre pour déclencher la mise à jour.
     * Si le paramètre de configuration 'inject_on_load' de ce bundle est défini sur 'true', ce setter doit être
     * capable d'accepter une instance de 'File' car le bundle en injectera une ici pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $illustrationFile
     */
    public function setIllustrationFile(?File $illustrationFile = null): void
    {
        $this->illustrationFile = $illustrationFile;

        if (null !== $illustrationFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getIllustrationFile(): ?File
    {
        return $this->illustrationFile;
    }

    public function setIllustrationName(?string $illustrationName): void
    {
        $this->illustrationName = $illustrationName;
    }

    public function getIllustrationName(): ?string
    {
        return $this->illustrationName;
    }

    public function setIllustrationSize(?int $illustrationSize): void
    {
        $this->illustrationSize = $illustrationSize;
    }

    public function getIllustrationSize(): ?int
    {
        return $this->illustrationSize;
    }

    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser Symfony Form),
     * assurez-vous qu'une instance de "UploadedFile" est injectée dans ce paramètre pour déclencher la mise à jour.
     * Si le paramètre de configuration 'inject_on_load' de ce bundle est défini sur 'true', ce setter doit être
     * capable d'accepter une instance de 'File' car le bundle en injectera une ici pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $illustrationtwoFile
     */
    public function setIllustrationtwoFile(?File $illustrationtwoFile = null): void
    {
        $this->illustrationtwoFile = $illustrationtwoFile;

        if (null !== $illustrationtwoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getIllustrationtwoFile(): ?File
    {
        return $this->illustrationtwoFile;
    }

    public function setIllustrationtwoName(?string $illustrationtwoName): void
    {
        $this->illustrationtwoName = $illustrationtwoName;
    }

    public function getIllustrationtwoName(): ?string
    {
        return $this->illustrationtwoName;
    }

    public function setIllustrationtwoSize(?int $illustrationtwoSize): void
    {
        $this->illustrationtwoSize = $illustrationtwoSize;
    }

    public function getIllustrationtwoSize(): ?int
    {
        return $this->illustrationtwoSize;
    }

    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser Symfony Form),
     * assurez-vous qu'une instance de "UploadedFile" est injectée dans ce paramètre pour déclencher la mise à jour.
     * Si le paramètre de configuration 'inject_on_load' de ce bundle est défini sur 'true', ce setter doit être
     * capable d'accepter une instance de 'File' car le bundle en injectera une ici pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $illustrationthirdFile
     */
    public function setIllustrationthirdFile(?File $illustrationthirdFile = null): void
    {
        $this->illustrationthirdFile = $illustrationthirdFile;

        if (null !== $illustrationthirdFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getIllustrationthirdFile(): ?File
    {
        return $this->illustrationthirdFile;
    }

    public function setIllustrationthirdName(?string $illustrationthirdName): void
    {
        $this->illustrationthirdName = $illustrationthirdName;
    }

    public function getIllustrationthirdName(): ?string
    {
        return $this->illustrationthirdName;
    }

    public function setIllustrationthirdSize(?int $illustrationthirdSize): void
    {
        $this->illustrationthirdSize = $illustrationthirdSize;
    }

    public function getIllustrationthirdSize(): ?int
    {
        return $this->illustrationthirdSize;
    }
    
}
