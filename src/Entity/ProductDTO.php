<?php

namespace App\Entity;

use App\Repository\ProductDTORepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductDTORepository::class)
 */
class ProductDTO
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @param mixed $id
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @ORM\Column(type="string", length=100)
     *  @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El nombre del producto debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre del producto no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150)
     ** @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La referencia debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La referencia no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=150)
     **  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "El formato debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El formato no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $format;

    /**
     * @ORM\Column(type="integer")
     */
    private $productSet;

    /**
     * @ORM\Column(type="string", length=150)
     *  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La zona debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La zona no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $zone;

    /**
     * @ORM\Column(type="string", length=150)
     **  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "El uso del producto debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El uso del producto no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $productUse;

    /**
     * @ORM\Column(type="string", length=150)
     *  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "El uso del producto debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El uso del producto no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $productType;

    /**
     * @ORM\Column(type="string", length=150)
     *  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "El decorado debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El decorado no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $decorated;

    /**
     * @ORM\Column(type="string", length=150)
     *  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "El nombre del material debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre del material no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $material;

    /**
     * @ORM\Column(type="string", length=150)
     *  @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La divisa debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "La divisa no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $currency;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(
     *     message= "El precio debe ser un numero positivo."
     * )
     */
    private $price;

    /**
     * @var int|null
     *
     * @ORM\Column(name="box", type="integer", nullable=true)
     */
    private $box;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero(
     *     message= "El descuente debe ser un numero no negativo."
     * )
     */
    private $discount;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero(
     *     message= "El stock debe ser un numero no negativo."
     * )
     */
    private $stock;

    private $imageCoverUrl;

    private $imageAmbientUrl;

    private $minImagesUrl;

    /**
     * @ORM\Column(type="string", length=100)
     *  @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El nombre del producto debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre del producto no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $brand;



    /**
     * @ORM\Column(type="string", length=100)
     *  @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "El nombre del pais de fabricacion del producto debe tener minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre del pais de fabricacion del producto no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $country;


    public function getBrand(): ?string
    {
        return $this->brand;
    }


    public function setBrand(string $brand): self
    {
        $this->brand = $brand;
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

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getProductSet(): ?int
    {
        return $this->productSet;
    }

    public function setProductSet(int $productSet): self
    {
        $this->productSet = $productSet;

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

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getProductUse(): ?string
    {
        return $this->productUse;
    }

    public function setProductUse(string $productUse): self
    {
        $this->productUse = $productUse;

        return $this;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }

    public function setProductType(string $productType): self
    {
        $this->productType = $productType;

        return $this;
    }

    public function getDecorated(): ?string
    {
        return $this->decorated;
    }

    public function setDecorated(string $decorated): self
    {
        $this->decorated = $decorated;

        return $this;
    }

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(string $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getStock(): ?array
    {
        return $this->stock;
    }

    public function setStock(array $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageCoverUrl(): ?string
    {
        return $this->imageCoverUrl;
    }

    /**
     * @param mixed $imageCoverUrl
     */
    public function setImageCoverUrl($imageCoverUrl): self
    {
        $this->imageCoverUrl = $imageCoverUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageAmbientUrl(): ?string
    {
        return $this->imageAmbientUrl;
    }

    /**
     * @param mixed $imageAmbientUrl
     */
    public function setImageAmbientUrl($imageAmbientUrl): self
    {
        $this->imageAmbientUrl = $imageAmbientUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinImagesUrl(): ?array
    {
        return $this->minImagesUrl;
    }

    /**
     * @param mixed $minImagesUrl
     */
    public function setMinImagesUrl(array $minImagesUrl): self
    {
        $this->minImagesUrl = $minImagesUrl;

        return $this;
    }


}
