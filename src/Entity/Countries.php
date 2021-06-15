<?php

namespace App\Entity;

use App\Repository\CountriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CountriesRepository::class)
 */
class Countries
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="country_code", type="string", length=2)
     *  @Assert\Length(
     *      min = 1,
     *      max = 2,
     *      minMessage = "El codigo del pais debe tener {{ limit }} caracter.",
     *      maxMessage = "El codigo del pais no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $countryCode;

    /**
     * @ORM\Column(name="country_name",type="string", length=80)
     *  @Assert\Length(
     *      min = 1,
     *      max = 80,
     *      minMessage = "El nombre del pais debe tener {{ limit }} caracter.",
     *      maxMessage = "El nombre del pais no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $countryName;

    /**
     * @ORM\OneToMany(targetEntity=Regions::class, mappedBy="country", orphanRemoval=true)
     */
    private $regions;

    public function __construct()
    {
        $this->regions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * @return Collection|Regions[]
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Regions $region): self
    {
        if (!$this->regions->contains($region)) {
            $this->regions[] = $region;
            $region->setCountry($this);
        }

        return $this;
    }

    public function removeRegion(Regions $region): self
    {
        if ($this->regions->removeElement($region)) {
            // set the owning side to null (unless already changed)
            if ($region->getCountry() === $this) {
                $region->setCountry(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return "";
    }
}
