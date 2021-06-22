<?php

namespace App\Entity\Admin;

use App\Repository\Admin\SocietyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocietyRepository::class)
 *
 */
class Society
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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $respFirstname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $ape;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlweb;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRs = false;

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
    private $isGroupeEntreprise = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $FirstActivity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $secondActivity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $projectDev;

    /**
     * @ORM\OneToMany(targetEntity=Member::class, mappedBy="society")
     */
    private $member;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->member = new ArrayCollection();
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

    public function getRespFirstname(): ?string
    {
        return $this->respFirstname;
    }

    public function setRespFirstname(?string $respFirstname): self
    {
        $this->respFirstname = $respFirstname;

        return $this;
    }

    public function getRespLastName(): ?string
    {
        return $this->respLastName;
    }

    public function setRespLastName(?string $respLastName): self
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

    public function getUrlweb(): ?string
    {
        return $this->urlweb;
    }

    public function setUrlweb(?string $urlweb): self
    {
        $this->urlweb = $urlweb;

        return $this;
    }

    public function getIsRs(): ?bool
    {
        return $this->isRs;
    }

    public function setIsRs(bool $isRs): self
    {
        $this->isRs = $isRs;

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

    public function getIsGroupeEntreprise(): ?bool
    {
        return $this->isGroupeEntreprise;
    }

    public function setIsGroupeEntreprise(bool $isGroupeEntreprise): self
    {
        $this->isGroupeEntreprise = $isGroupeEntreprise;

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
     * @return Collection|Member[]
     */
    public function getMember(): Collection
    {
        return $this->member;
    }

    public function addMember(Member $member): self
    {
        if (!$this->member->contains($member)) {
            $this->member[] = $member;
            $member->setSociety($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->member->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getSociety() === $this) {
                $member->setSociety(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
