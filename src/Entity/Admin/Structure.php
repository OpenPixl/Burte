<?php

namespace App\Entity\Admin;

use App\Repository\Admin\StructureRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StructureRepository::class)
 * @ORM\HasLifecycleCallbacks
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
}
