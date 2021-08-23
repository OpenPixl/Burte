<?php

namespace App\Entity\GestApp;

use App\Entity\Admin\Member;
use App\Repository\GestApp\EventRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable()
 */
class Event
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="events")
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublish;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=EventGal::class, mappedBy="event")
     */
    private $eventGals;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $public;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeComplement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeZipcode;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $placeCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactEventEmail;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $contactEventPhone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlFacebookEvent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlInstagramEvent;


    /**
     * Insertion de l'image mise en avant liée à un article
     * NOTE : Il ne s'agit pas d'un champ mappé des métadonnées de l'entité, mais d'une simple propriété.
     *
     * @Vich\UploadableField(mapping="event_visual", fileNameProperty="visuelName", size="visuelSize")
     * @var File|null
     */
    private $visuelFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $visuelName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $visuelSize;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $eventAt;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $eventStartAt;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $eventFinishAt;

    /**
     * @ORM\Column(type="time",nullable=true)
     */
    private $eventtimeAt;

    public function __construct()
    {
        $this->eventGals = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getIsPublish(): ?bool
    {
        return $this->isPublish;
    }

    public function setIsPublish(bool $isPublish): self
    {
        $this->isPublish = $isPublish;

        return $this;
    }

    public function getIsValidBy(): ?bool
    {
        return $this->isValidBy;
    }

    public function setIsValidBy(bool $isValidBy): self
    {
        $this->isValidBy = $isValidBy;

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

    /**
     * @return Collection|EventGal[]
     */
    public function getEventGals(): Collection
    {
        return $this->eventGals;
    }

    public function addEventGal(EventGal $eventGal): self
    {
        if (!$this->eventGals->contains($eventGal)) {
            $this->eventGals[] = $eventGal;
            $eventGal->setEvent($this);
        }

        return $this;
    }

    public function removeEventGal(EventGal $eventGal): self
    {
        if ($this->eventGals->removeElement($eventGal)) {
            // set the owning side to null (unless already changed)
            if ($eventGal->getEvent() === $this) {
                $eventGal->setEvent(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }

    public function getPublic(): ?string
    {
        return $this->public;
    }

    public function setPublic(?string $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getPlaceAddress(): ?string
    {
        return $this->placeAddress;
    }

    public function setPlaceAddress(?string $placeAddress): self
    {
        $this->placeAddress = $placeAddress;

        return $this;
    }

    public function getPlaceComplement(): ?string
    {
        return $this->placeComplement;
    }

    public function setPlaceComplement(?string $placeComplement): self
    {
        $this->placeComplement = $placeComplement;

        return $this;
    }

    public function getPlaceZipcode(): ?string
    {
        return $this->placeZipcode;
    }

    public function setPlaceZipcode(?string $placeZipcode): self
    {
        $this->placeZipcode = $placeZipcode;

        return $this;
    }

    public function getPlaceCity(): ?string
    {
        return $this->placeCity;
    }

    public function setPlaceCity(?string $placeCity): self
    {
        $this->placeCity = $placeCity;

        return $this;
    }

    public function getContactEventEmail(): ?string
    {
        return $this->contactEventEmail;
    }

    public function setContactEventEmail(?string $contactEventEmail): self
    {
        $this->contactEventEmail = $contactEventEmail;

        return $this;
    }

    public function getContactEventPhone(): ?string
    {
        return $this->contactEventPhone;
    }

    public function setContactEventPhone(?string $contactEventPhone): self
    {
        $this->contactEventPhone = $contactEventPhone;

        return $this;
    }

    public function getUrlFacebookEvent(): ?string
    {
        return $this->urlFacebookEvent;
    }

    public function setUrlFacebookEvent(?string $urlFacebookEvent): self
    {
        $this->urlFacebookEvent = $urlFacebookEvent;

        return $this;
    }

    public function getUrlInstagramEvent(): ?string
    {
        return $this->urlInstagramEvent;
    }

    public function setUrlInstagramEvent(?string $urlInstagramEvent): self
    {
        $this->urlInstagramEvent = $urlInstagramEvent;

        return $this;
    }

    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser Symfony Form),
     * assurez-vous qu'une instance de "UploadedFile" est injectée dans ce paramètre pour déclencher la mise à jour.
     * Si le paramètre de configuration 'inject_on_load' de ce bundle est défini sur 'true', ce setter doit être
     * capable d'accepter une instance de 'File' car le bundle en injectera une ici pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $visuelFile
     */
    public function setvisuelFile(?File $visuelFile = null): void
    {
        $this->visuelFile = $visuelFile;

        if (null !== $visuelFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getvisuelFile(): ?File
    {
        return $this->visuelFile;
    }

    public function setvisuelName(?string $visuelName): void
    {
        $this->visuelName = $visuelName;
    }

    public function getvisuelName(): ?string
    {
        return $this->visuelName;
    }

    public function setvisuelSize(?int $visuelSize): void
    {
        $this->visuelSize = $visuelSize;
    }

    public function getvisuelSize(): ?int
    {
        return $this->visuelSize;
    }


    public function getEventAt(): ?\DateTimeInterface
    {
        return $this->eventAt;
    }

    public function setEventAt(?\DateTimeInterface $eventAt): self
    {
        $this->eventAt = $eventAt;

        return $this;
    }

    public function getEventStartAt(): ?\DateTimeInterface
    {
        return $this->eventStartAt;
    }

    public function setEventStartAt(?\DateTimeInterface $eventStartAt): self
    {
        $this->eventStartAt = $eventStartAt;

        return $this;
    }

    public function getEventFinishAt(): ?\DateTimeInterface
    {
        return $this->eventFinishAt;
    }

    public function setEventFinishAt(?\DateTimeInterface $eventFinishAt): self
    {
        $this->eventFinishAt = $eventFinishAt;

        return $this;
    }

    public function getEventtimeAt(): ?\DateTimeInterface
    {
        return $this->eventtimeAt;
    }

    public function setEventtimeAt(\DateTimeInterface $eventtimeAt): self
    {
        $this->eventtimeAt = $eventtimeAt;

        return $this;
    }

}
