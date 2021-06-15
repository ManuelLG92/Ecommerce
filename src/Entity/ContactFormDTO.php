<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class ContactFormDTO
{


    /**
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El nombre debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre producto  no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $name;
    /**
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El apellido debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El apellido producto  no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $surname;

    /**
     * @Assert\Length(
     *      min = 7,
     *      max = 32,
     *      minMessage = "El numero de telefono debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El umero de telefono  no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $phone;

    /**
     * @Assert\Email (message="Introduce un email valido")
     */
    private $email;

    /**
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El nombre del país debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre del país no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $country;

    /**
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El nombre de la región debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre de la región no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $region;

    /**
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El nombre de la ciudad debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre de la ciudad no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $city;

    /**
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "La informacion adicional no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private ?string $additionalInfo;



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }


    public function setSurname($surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }


    public function setPhone($phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }


    public function getCountry(): ?string
    {
        return $this->country;
    }


    public function setCountry($country): self
    {
        $this->country = $country;
        return $this;
    }


    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion($region): self
    {
        $this->region = $region;
        return $this;
    }


    public function getCity(): ?string
    {
        return $this->city;
    }


    public function setCity($city): self
    {
        $this->city = $city;
        return $this;
    }


    public function getAdditionalInfo(): ?string
    {
        return $this->additionalInfo;
    }


    public function setAdditionalInfo($additionalInfo): self
    {
        $this->additionalInfo = $additionalInfo;
        return $this;
    }





}
