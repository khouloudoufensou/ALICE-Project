<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Merci d'indiquer le titre")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank (message="Merci d'indiquer la discription")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Merci d'indiquer la période")
     * 
     */
    private $period;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank (message="Merci d'indiquer le prix")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank (message="Merci d'indiquer la date de début")
     */
    private $startAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Merci d'indiquer le nom et le prénom de l'intervenant")
     */
    private $speaker;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank (message="Merci d'indiquer la présentation de l'intervenant")
     */
    private $presSpeaker;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank (message="Merci d'indiquer le nombre de place disponible")
     */
    private $seatingCapacity;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank (message="Merci d'indiquer le programme")
     */
    private $program;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Merci d'insérer une photo")
     */
    private $picture;

     // my fonctions
     public function contentellipsis(): string
     {
         $text = substr($this->description, 0, 100);
         $dote = strlen($this->description)> 100?'...':'';
         return $text.$dote;
     }

    //  pictures function
    public function getImageDirectory(): string
    {
        return 'formation';
    }

    public function getImagePath(): string
    {
        return 'images/formation/'.$this->picture;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getSpeaker(): ?string
    {
        return $this->speaker;
    }

    public function setSpeaker(string $speaker): self
    {
        $this->speaker = $speaker;

        return $this;
    }

    public function getPresSpeaker(): ?string
    {
        return $this->presSpeaker;
    }

    public function setPresSpeaker(string $presSpeaker): self
    {
        $this->presSpeaker = $presSpeaker;

        return $this;
    }

    public function getSeatingCapacity(): ?int
    {
        return $this->seatingCapacity;
    }

    public function setSeatingCapacity(int $seatingCapacity): self
    {
        $this->seatingCapacity = $seatingCapacity;

        return $this;
    }

    public function getProgram(): ?string
    {
        return $this->program;
    }

    public function setProgram(string $program): self
    {
        $this->program = $program;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
