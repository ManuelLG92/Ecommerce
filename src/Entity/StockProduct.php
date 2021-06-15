<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="stock_product",
 *     indexes={
 *          @ORM\Index(name="fk_stock_id_product", columns={"id_product"}),
 *          @ORM\Index(name="fk_stock_id_warehouse", columns={"id_warehouse"})})
 * @ORM\Entity(repositoryClass="App\Repository\StockRepository")
 */
class StockProduct
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
     * @ORM\Column(name="id_warehouse", type="integer")
     * @ORM\ManyToOne(targetEntity="Warehouse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_warehouse", referencedColumnName="id")
     * })
     * @Assert\Positive(
     *     message="El id del almacen debe ser positivo"
     * )
     */

    private $idWarehouse;


    /**
     * @ORM\Column(name="id_product", type="integer")
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     * })
     * @Assert\Positive(
     *     message="El id del producto debe ser positivo"
     * )
     */
    private $idProduct;
    /**
     * @var int
     * @ORM\Column(name="stock", type="integer")
     * @Assert\PositiveOrZero(
     *     message=" El stock debe ser positivo o 0"
     * )
     */
    private $stock;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getIdWarehouse(): ?int
    {
        return $this->idWarehouse;
    }

    public function setIdWarehouse(?int $idWarehouse): self
    {
        $this->idWarehouse = $idWarehouse;

        return $this;
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




}
