<?php

namespace App\Entity;

use App\Repository\ProductDTORepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class CartProductDTO
{

    private $id;

    private $name;

    private $reference;

    private $material;

    private $imageCoverUrl;



    private $brand;

    private $country;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

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


    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(string $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getImageCoverUrl(): ?string
    {
        return $this->imageCoverUrl;
    }

    public function setImageCoverUrl($imageCoverUrl): self
    {
        $this->imageCoverUrl = $imageCoverUrl;
        return $this;
    }



}
