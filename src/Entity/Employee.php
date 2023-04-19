<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToOne]
    private ?Rol $rol = null;

    #[ORM\Column(length: 255)]
    private ?string $identifying = null;

    #[ORM\ManyToOne]
    private ?Team $team = null;

    #[ORM\ManyToOne]
    private ?Position $position = null;

    #[ORM\ManyToOne]
    private ?Area $area = null;

    #[ORM\ManyToOne]
    private ?Contract $typeOfContract = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $finishDate = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\ManyToOne]
    private ?Status $status = null;

    #[ORM\ManyToOne]
    private ?Manager $manager = null;

    #[ORM\ManyToOne]
    private ?Period $period = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $firstPeriod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $secondPeriod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $thirdPeriod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fourthPeriod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fifthPeriod = null;

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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getRol(): ?Rol
    {
        return $this->rol;
    }

    public function setRol(?Rol $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getIdentifying(): ?string
    {
        return $this->identifying;
    }

    public function setIdentifying(string $identifying): self
    {
        $this->identifying = $identifying;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getTypeOfContract(): ?Contract
    {
        return $this->typeOfContract;
    }

    public function setTypeOfContract(?Contract $typeOfContract): self
    {
        $this->typeOfContract = $typeOfContract;

        return $this;
    }

      public function __call($typeOfContract, $title)
    {
        return $this->getTypeOfContract();
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFinishDate(): ?\DateTimeInterface
    {
        return $this->finishDate;
    }

    public function setFinishDate(\DateTimeInterface $finishDate): self
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getPeriod(): ?Period
    {
        return $this->period;
    }

    public function setPeriod(?Period $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getFirstPeriod(): ?\DateTimeInterface
    {
        return $this->firstPeriod;
    }

    public function setFirstPeriod(?\DateTimeInterface $firstPeriod): self
    {
        $this->firstPeriod = $firstPeriod;

        return $this;
    }

    public function getSecondPeriod(): ?\DateTimeInterface
    {
        return $this->secondPeriod;
    }

    public function setSecondPeriod(?\DateTimeInterface $secondPeriod): self
    {
        $this->secondPeriod = $secondPeriod;

        return $this;
    }

    public function getThirdPeriod(): ?\DateTimeInterface
    {
        return $this->thirdPeriod;
    }

    public function setThirdPeriod(?\DateTimeInterface $thirdPeriod): self
    {
        $this->thirdPeriod = $thirdPeriod;

        return $this;
    }

    public function getFourthPeriod(): ?\DateTimeInterface
    {
        return $this->fourthPeriod;
    }

    public function setFourthPeriod(?\DateTimeInterface $fourthPeriod): self
    {
        $this->fourthPeriod = $fourthPeriod;

        return $this;
    }

    public function getFifthPeriod(): ?\DateTimeInterface
    {
        return $this->fifthPeriod;
    }

    public function setFifthPeriod(?\DateTimeInterface $fifthPeriod): self
    {
        $this->fifthPeriod = $fifthPeriod;

        return $this;
    }
}
