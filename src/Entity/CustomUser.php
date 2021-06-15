<?php

namespace App\Entity;

use App\Repository\CustomUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="custom_user")
 * @ORM\Entity(repositoryClass=CustomUserRepository::class)
 */
class CustomUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=150)
     *  @Assert\Length(
     *      min = 2,
     *      max = 150,
     *      minMessage = "El nombre del usuario debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre del usuario no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $name;

    /**
     * @ORM\Column(name="surname", type="string", length=150)
     *  @Assert\Length(
     *      min = 2,
     *      max = 150,
     *      minMessage = "El apellido del usuario debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El apellido del usuario no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $surname;

    /**
     * @ORM\Column(name="company_name",type="string", length=255)
     *  @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "El nombre de la empresa debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre de la empresa no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $companyName;

    /**
     * @ORM\Column(name="nif",type="string", length=100)
     *  @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "El NIF del usuario debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El NIF del usuario no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $NIF;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Countries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country", referencedColumnName="id")
     * })
     * @Assert\Valid
     */
    private $country;

    /**
     * @ORM\Column(name="address", type="string", length=255)
     *  @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "La direccion del usuario debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La direccion del usuario no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $address;

    /**
     * @ORM\Column(name="region", type="string", length=150)
     *  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La region del usuario debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La region del usuario no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $region;

    /**
     * @ORM\Column(name="city", type="string", length=150)
     *  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La ciudad del usuario debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La ciudad del usuario no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $city;

    /**
     * @ORM\Column(name="postal_code", type="string", length=100)
     *  @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El codigo postal del usuario debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El codigo postal del usuario no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $postalCode;

    /**
     * @ORM\Column(name="phone", type="string", length=100)
     *  @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El numero de telefono del usuario debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El numero de telefono del usuario no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email(
     *     message = "El email '{{ value }}' no es valido."
     * )
     */
    private $email;

    /**
     * @ORM\Column(name="additional_information", type="string", length=255, nullable=true)
     */
    private $additionalInformation;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getNIF(): ?string
    {
        return $this->NIF;
    }

    public function setNIF(string $NIF): self
    {
        $this->NIF = $NIF;

        return $this;
    }

    public function getCountry(): ?Countries
    {
        return $this->country;
    }

    public function setCountry(Countries $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?string $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    public function __toString()
    {
        return "";
    }
}
