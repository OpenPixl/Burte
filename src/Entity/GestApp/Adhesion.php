<?php

namespace App\Entity\GestApp;

use App\Repository\GestApp\AdhesionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdhesionRepository::class)
 */
class Adhesion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $society;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $gerant;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="boolean")
     */
    private $isGroupeEntreprendre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstActivity;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSociety(): ?string
    {
        return $this->society;
    }

    public function setSociety(string $society): self
    {
        $this->society = $society;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getGerant(): ?string
    {
        return $this->gerant;
    }

    public function setGerant(string $gerant): self
    {
        $this->gerant = $gerant;

        return $this;
    }

    public function getUrlWeb(): ?string
    {
        return $this->urlWeb;
    }

    public function setUrlWeb(string $urlWeb): self
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

    public function getIsGroupeEntreprendre(): ?bool
    {
        return $this->isGroupeEntreprendre;
    }

    public function setIsGroupeEntreprendre(bool $isGroupeEntreprendre): self
    {
        $this->isGroupeEntreprendre = $isGroupeEntreprendre;

        return $this;
    }

    public function getFirstActivity(): ?string
    {
        return $this->firstActivity;
    }

    public function setFirstActivity(string $firstActivity): self
    {
        $this->firstActivity = $firstActivity;

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
