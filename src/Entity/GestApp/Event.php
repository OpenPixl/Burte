<?php

namespace App\Entity\GestApp;

use App\Entity\Admin\Member;
use App\Repository\GestApp\EventRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
}
