<?php

namespace App\Entity;

use App\Repository\ProductImageMinRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="product_images_min")
 * @ORM\Entity(repositoryClass=ProductImageMinRepository::class)
 */
class ProductImageMin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="id_product",type="integer")
     * @Assert\Positive(
     *     message=" El id del producto debe ser positivo"
     * )
     */
    private $idProduct;

    /**
     * @ORM\Column( name="image_url",type="string", length=255)
     * @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "La url de la imagen debe tener como minimo {{ limit }} caracter.",
     *      maxMessage = "la url de la imagen no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $imageUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
    }

    public function setIdProduct(int $idProduct): self
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
}
