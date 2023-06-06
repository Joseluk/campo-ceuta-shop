<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="stock")
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Producto", inversedBy="stock")
     * @ORM\JoinColumn(name="id_producto", referencedColumnName="id")
     */
    private $producto;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Stock
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * @param mixed $producto
     * @return Stock
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     * @return Stock
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @param mixed $precio
     * @return Stock
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
        return $this;
    }


}

