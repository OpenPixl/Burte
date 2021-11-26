<?php

namespace App\Entity\Gestapp;

use App\Entity\Admin\Member;
use App\Repository\Gestapp\RecommandationRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RecommandationRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Recommandation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"recommandation_list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"recommandation_list"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"recommandation_list"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="recommandations")
     * @Groups({"recommandation_list"})
     */
    private $member;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"recommandation_list"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientFirstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientLastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientComplement;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $clientZipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientEmail;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $clientDesk;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $clientGsm;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $recoPrice;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $recoState;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="authorReco")
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRead = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFirstView;

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

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

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

    public function getClientFirstname(): ?string
    {
        return $this->clientFirstname;
    }

    public function setClientFirstname(?string $clientFirstname): self
    {
        $this->clientFirstname = $clientFirstname;

        return $this;
    }

    public function getClientLastname(): ?string
    {
        return $this->clientLastname;
    }

    public function setClientLastname(string $clientLastname): self
    {
        $this->clientLastname = $clientLastname;

        return $this;
    }

    public function getClientAddress(): ?string
    {
        return $this->clientAddress;
    }

    public function setClientAddress(string $clientAddress): self
    {
        $this->clientAddress = $clientAddress;

        return $this;
    }

    public function getClientComplement(): ?string
    {
        return $this->clientComplement;
    }

    public function setClientComplement(string $clientComplement): self
    {
        $this->clientComplement = $clientComplement;

        return $this;
    }

    public function getClientZipcode(): ?string
    {
        return $this->clientZipcode;
    }

    public function setClientZipcode(string $clientZipcode): self
    {
        $this->clientZipcode = $clientZipcode;

        return $this;
    }

    public function getClientCity(): ?string
    {
        return $this->clientCity;
    }

    public function setClientCity(?string $clientCity): self
    {
        $this->clientCity = $clientCity;

        return $this;
    }

    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    public function setClientEmail(?string $clientEmail): self
    {
        $this->clientEmail = $clientEmail;

        return $this;
    }

    public function getClientDesk(): ?string
    {
        return $this->clientDesk;
    }

    public function setClientDesk(string $clientDesk): self
    {
        $this->clientDesk = $clientDesk;

        return $this;
    }

    public function getClientGsm(): ?string
    {
        return $this->clientGsm;
    }

    public function setClientGsm(?string $clientGsm): self
    {
        $this->clientGsm = $clientGsm;

        return $this;
    }

    public function getRecoPrice(): ?string
    {
        return $this->recoPrice;
    }

    public function setRecoPrice(?string $recoPrice): self
    {
        $this->recoPrice = $recoPrice;

        return $this;
    }

    public function getRecoState(): ?string
    {
        return $this->recoState;
    }

    public function setRecoState(?string $recoState): self
    {
        $this->recoState = $recoState;

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

    public function getAuthor(): ?Member
    {
        return $this->author;
    }

    public function setAuthor(?Member $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getIsFirstView(): ?bool
    {
        return $this->isFirstView;
    }

    public function setIsFirstView(bool $isFirstView): self
    {
        $this->isFirstView = $isFirstView;

        return $this;
    }
}
