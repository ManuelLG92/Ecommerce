<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, nullable=false)
     * @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "El nombre del producto debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre del producto  no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=150, nullable=false)
     * @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La referencia del producto debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La referencia del producto  no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $reference;

    /**
     * @var int
     *
     * @ORM\Column(name="set_product", type="integer", nullable=false)
     * * @Assert\Positive (
     *     message="El set del producto debe ser positivo"
     * )
     */
    private $setProduct;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var int|null
     *
     * @ORM\Column(name="discount", type="integer", nullable=true)
     * @Assert\PositiveOrZero(
     *     message="El descuento debe ser positivo o cero"
     * )
     */
    private $discount;

    /**
     * @var int|null
     *
     * @ORM\Column(name="box", type="integer", nullable=true)
     * @Assert\Positive (
     *     message="El numero de piezas en caja debe ser positivo"
     * )
     */
    private $box;


    /**
     * @var \Currency
     *
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_currency", referencedColumnName="id")
     * })
     * @Assert\Valid
     */
    private $currency;

    /**
     * @var \Decorated
     *
     * @ORM\ManyToOne(targetEntity="Decorated")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_decorated", referencedColumnName="id")
     * })
     * @Assert\Valid
     */
    private $idDecorated;

    /**
     * @var \Format
     *
     * @ORM\ManyToOne(targetEntity="Format")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_format", referencedColumnName="id")
     * })
     * @Assert\Valid
     */
    private $idFormat;

    /**
     * @var \Material
     *
     * @ORM\ManyToOne(targetEntity="Material")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_material", referencedColumnName="id")
     * })
     * @Assert\Valid
     */
    private $idMaterial;

    /**
     * @var \ProductType
     *
     * @ORM\ManyToOne(targetEntity="ProductType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type", referencedColumnName="id")
     * })
     * @Assert\Valid
     */
    private $idType;

    /**
     * @var \ProductUse
     *
     * @ORM\ManyToOne(targetEntity="ProductUse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_use", referencedColumnName="id")
     * })
     * @Assert\Valid
     */
    private $idUse;

    /**
     * @var \Zone
     *
     * @ORM\ManyToOne(targetEntity="Zone")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_zone", referencedColumnName="id")
     * })
     * @Assert\Valid()
     */
    private $idZone;

    /**
     * @var \Brand
     *
     * @ORM\ManyToOne(targetEntity=Brand::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Countries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country", referencedColumnName="id")
     * })
     * @Assert\Valid()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La imagen de portada del producto debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La imagen de portada del producto  no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $imageCover;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La imagen de ambiente del producto debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La imagen de ambiente del producto  no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $imageAmbient;




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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSetProduct(): ?int
    {
        return $this->setProduct;
    }

    public function setSetProduct(int $setProduct): self
    {
        $this->setProduct = $setProduct;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBox(): ?int
    {
        return $this->box;
    }

    /**
     * @param int|null $box
     */
    public function setBox(?int $box): self
    {
        $this->box = $box;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getIdCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setIdCurrency(?Currency $idCurrency): self
    {
        $this->currency = $idCurrency;

        return $this;
    }

    public function getIdDecorated(): Decorated
    {
        return $this->idDecorated;
    }

    public function setIdDecorated(?Decorated $idDecorated): self
    {
        $this->idDecorated = $idDecorated;

        return $this;
    }

    public function getIdFormat(): Format
    {
        return $this->idFormat;
    }

    public function setIdFormat(?Format $idFormat): self
    {
        $this->idFormat = $idFormat;

        return $this;
    }

    public function getIdMaterial(): ?Material
    {
        return $this->idMaterial;
    }

    public function setIdMaterial(?Material $idMaterial): self
    {
        $this->idMaterial = $idMaterial;

        return $this;
    }

    public function getIdType(): ?ProductType
    {
        return $this->idType;
    }

    public function setIdType(?ProductType $idType): self
    {
        $this->idType = $idType;

        return $this;
    }

    public function getIdUse(): ?ProductUse
    {
        return $this->idUse;
    }

    public function setIdUse(?ProductUse $idUse): self
    {
        $this->idUse = $idUse;

        return $this;
    }

    public function getIdZone(): ?Zone
    {
        return $this->idZone;
    }

    public function setIdZone(?Zone $idZone): self
    {
        $this->idZone = $idZone;

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

    public function getImageCover(): ?string
    {
        return $this->imageCover;
    }

    public function setImageCover(?string $imageCover): self
    {
        $this->imageCover = $imageCover;

        return $this;
    }

    public function getImageAmbient(): ?string
    {
        return $this->imageAmbient;
    }

    public function setImageAmbient(?string $imageAmbient): self
    {
        $this->imageAmbient = $imageAmbient;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }


}
