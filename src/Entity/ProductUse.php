<?php

namespace App\Entity;

use App\Repository\ProductUseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="product_use")
 * @ORM\Entity(repositoryClass=ProductUseRepository::class)
 */
class ProductUse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name",type="string", length=100)
     * @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "El nombre del uso debe tener como minimo {{ limit }} caracter.",
     *      maxMessage = "El nombre del uso  no puede tener mas de {{ limit }} caracteres"
     * )
     */
    private $name;

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
}
