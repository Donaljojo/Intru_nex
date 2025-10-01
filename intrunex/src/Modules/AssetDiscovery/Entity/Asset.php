<?php

namespace App\Modules\AssetDiscovery\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

#[ORM\Entity]
#[ORM\Table(name: "asset_discovery_asset")]
class Asset
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;
    // just under private ?int $id
//#[ORM\Column(type: "integer")]
//private int $userAssetNumber = 0;
#[ORM\Column(type: "integer", nullable: true)]
private ?int $userAssetNumber = null;


public function getUserAssetNumber(): int
{
    return $this->userAssetNumber;
}

public function setUserAssetNumber(int $num): self
{
    $this->userAssetNumber = $num;
    return $this;
}


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", length: 45, nullable: true)]
    #[Assert\Ip]
    private ?string $ipAddress = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $domain = null;

    #[ORM\Column(type: "string", length: 50)]
    private string $type;

    #[ORM\Column(type: "string", length: 50)]
    private string $status;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    // 🔹 Phase 1 Profiling fields
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $operatingSystem = null;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $openPorts = null;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $lastProfiledAt = null;

    // 🔹 Phase 2 Vulnerability Scan fields
    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $lastVulnerabilityScanAt = null;

    // ---------------------------------
    // User getter and setter
    // ---------------------------------
    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    // ---------------------------------
    // Other Getters and Setters
    // ---------------------------------
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): self
    {
        $this->domain = $domain;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
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

    // ---------------------------------
    // New fields for Profiling
    // ---------------------------------
    public function getOperatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    public function setOperatingSystem(?string $operatingSystem): self
    {
        $this->operatingSystem = $operatingSystem;
        return $this;
    }

    public function getOpenPorts(): ?array
    {
        return $this->openPorts;
    }

    public function setOpenPorts(?array $openPorts): self
    {
    // Ensure all ports are stored as strings
        if ($openPorts !== null) {
        $openPorts = array_map(fn($p) => (string) $p, $openPorts);
    }

    $this->openPorts = $openPorts;
    return $this;
    }

    public function getLastProfiledAt(): ?\DateTimeImmutable
    {
        return $this->lastProfiledAt;
    }

    public function setLastProfiledAt(?\DateTimeImmutable $lastProfiledAt): self
    {
        $this->lastProfiledAt = $lastProfiledAt;
        return $this;
    }

    // ---------------------------------
    // New fields for Vulnerability Scan
    // ---------------------------------
    public function getLastVulnerabilityScanAt(): ?\DateTimeImmutable
    {
        return $this->lastVulnerabilityScanAt;
    }

    public function setLastVulnerabilityScanAt(?\DateTimeImmutable $lastVulnerabilityScanAt): self
    {
        $this->lastVulnerabilityScanAt = $lastVulnerabilityScanAt;
        return $this;
    }
}



