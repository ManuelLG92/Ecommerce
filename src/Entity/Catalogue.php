<?php

namespace App\Entity;

use App\Repository\CatalogueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatalogueRepository::class)
 */
class Catalogue
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
    private ?string $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $hasAgentVisibility;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHasAgentVisibility(): ?bool
    {
        return $this->hasAgentVisibility;
    }

    public function setHasAgentVisibility(bool $hasAgentVisibility): self
    {
        $this->hasAgentVisibility = $hasAgentVisibility;

        return $this;
    }
}
