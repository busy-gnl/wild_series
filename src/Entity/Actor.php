<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ActorRepository;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTimeInterface;
//Ici on importe le package Vich, que l’on utilisera sous l’alias “Vich”

use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[Vich\Uploadable]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;


    #[Vich\UploadableField(mapping: 'actor_file', fileNameProperty: 'picture')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $actorFile = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DatetimeInterface $updatedAt = null;


    #[ORM\ManyToMany(targetEntity: Program::class, inversedBy: 'actors')]
    private Collection $programs;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
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

    /**
     * @return Collection<int, Program>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        $this->programs->removeElement($program);

        return $this;
    }

    /**
     * Get the value of actorFile
     */
    public function getActorFile(): ?File
    {
        return $this->actorFile;
    }

    /**
     * Set the value of actorFile
     *
     * @return  self
     */
    public function setActorFile(File $image = null): Actor
    {
        $this->actorFile = $image;

        if ($image) {

            $this->updatedAt = new \DateTime('now');

            return $this;
        }
    }

    /**
     * Get the value of updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
