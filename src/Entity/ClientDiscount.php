<?php

namespace App\Entity;

use App\Repository\ClientDiscountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientDiscountRepository::class)
 */
class ClientDiscount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProductType::class)
     * @Assert\Valid
     */
    private $ProductTypeDiscount;

    /**
     * @ORM\ManyToOne(targetEntity=CustomUser::class)
     * @Assert\Valid
     */
    private $CustomUser;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero(
     *     message="El descuento debe ser un numero positivo o cero"
     * )
     * @Assert\LessThanOrEqual(
     *     value = 100,
     *     message="El descuento no puede ser superior a 100"
     * )
     */
    private $Discount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductTypeDiscount(): ?ProductType
    {
        return $this->ProductTypeDiscount;
    }

    public function setProductTypeDiscount(?ProductType $ProductTypeDiscount): self
    {
        $this->ProductTypeDiscount = $ProductTypeDiscount;

        return $this;
    }

    public function getCustomUser(): ?CustomUser
    {
        return $this->CustomUser;
    }

    public function setCustomUser(?CustomUser $CustomUser): self
    {
        $this->CustomUser = $CustomUser;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->Discount;
    }

    public function setDiscount(int $Discount): self
    {
        $this->Discount = $Discount;

        return $this;
    }
}
