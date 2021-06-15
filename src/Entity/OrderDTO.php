<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;


class OrderDTO
{

    private $id;

    private $Reference;

    private $quantity;

    private $created_at;

    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=CustomUser::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getUser(): ?CustomUser
    {
        return $this->user;
    }


    public function setUser(CustomUser  $user): self
    {
        $this->user = $user;
        return $this;
    }


    public function getQuantity(): ?int
    {
        return $this->quantity;
    }


    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return  $this;
    }



    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->Reference;
    }

    public function setReference(?string $Reference): self
    {
        $this->Reference = $Reference;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }


}
