<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "El nombre de la ciudad debe tener {{ limit }} caracter.",
     *      maxMessage = "El nombre de la ciudad no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $name;

    /**
     * @ORM\Column(name="region_id", type="string", length=150)
     *  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La region del usuario debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La region del usuario no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $region;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive (
     *     message="El codigo de la ciudad debe ser positivo")
     */
    private $code;

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

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }
}
