<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NOM;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PRENOM;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ADRESSE;

    /**
     * @ORM\Column(type="integer")
     */
    private $TELEPHONE;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $EMAIL;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $DESCRIPTION;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNOM(): ?string
    {
        return $this->NOM;
    }

    public function setNOM(string $NOM): self
    {
        $this->NOM = $NOM;

        return $this;
    }

    public function getPRENOM(): ?string
    {
        return $this->PRENOM;
    }

    public function setPRENOM(string $PRENOM): self
    {
        $this->PRENOM = $PRENOM;

        return $this;
    }

    public function getADRESSE(): ?string
    {
        return $this->ADRESSE;
    }

    public function setADRESSE(string $ADRESSE): self
    {
        $this->ADRESSE = $ADRESSE;

        return $this;
    }

    public function getTELEPHONE(): ?int
    {
        return $this->TELEPHONE;
    }

    public function setTELEPHONE(int $TELEPHONE): self
    {
        $this->TELEPHONE = $TELEPHONE;

        return $this;
    }

    public function getEMAIL(): ?string
    {
        return $this->EMAIL;
    }

    public function setEMAIL(string $EMAIL): self
    {
        $this->EMAIL = $EMAIL;

        return $this;
    }

    public function getDESCRIPTION(): ?string
    {
        return $this->DESCRIPTION;
    }

    public function setDESCRIPTION(?string $DESCRIPTION): self
    {
        $this->DESCRIPTION = $DESCRIPTION;

        return $this;
    }
}
