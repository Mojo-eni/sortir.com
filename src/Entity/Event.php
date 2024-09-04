<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 10,
        max: 100,
        minMessage: "Le nom de la sortie ne peut pas être inférieur à 10 caractères",
        maxMessage: "Le nom de la sortie ne peut pas être supérieur à 100 caractères")]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan(
        'today 23:59:59',
        message:"Le début de la sortie ne peut pas être fixé avant demain")]
    private ?\DateTimeInterface $eventStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\LessThan(
        propertyPath: 'eventStart',
        message: "La date limite d'inscription ne peut pas être postérieure à la date de la sortie")]
    #[Assert\GreaterThan(
        'today',
        message:"La date limite d'inscription à la sortie ne peut pas être fixée avant demain")]
    private ?\DateTimeInterface $participationDeadline = null;

    #[ORM\Column]
    #[Assert\Range(
        minMessage: "Le nombre de participants ne peut pas être inférieur à 1",
        min: 1)]
    private ?int $participantLimit = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(
        minMessage: "La durée de la sortie ne peut pas être inférieure à 1",
        min: 1)]
    private ?int $duration = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organizer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEventStart(): ?\DateTimeInterface
    {
        return $this->eventStart;
    }

    public function setEventStart(\DateTimeInterface $eventStart): static
    {
        $this->eventStart = $eventStart;

        return $this;
    }

    public function getParticipationDeadline(): ?\DateTimeInterface
    {
        return $this->participationDeadline;
    }

    public function setParticipationDeadline(\DateTimeInterface $participationDeadline): static
    {
        $this->participationDeadline = $participationDeadline;

        return $this;
    }

    public function getParticipantLimit(): ?int
    {
        return $this->participantLimit;
    }

    public function setParticipantLimit(int $participantLimit): static
    {
        $this->participantLimit = $participantLimit;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }
}
