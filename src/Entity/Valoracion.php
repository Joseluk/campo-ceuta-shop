<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="valoracion")
 */
class Valoracion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="Producto")
     * @ORM\JoinColumn(name="id_producto", referencedColumnName="id")
     */
    private $producto;

    /**
     * @ORM\Column(type="text")
     */
    private $opinion;

    /**
     * @ORM\Column(type="float")
     */
    private $puntuacion;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Valoracion
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     * @return Valoracion
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
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
     * @return Valoracion
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpinion()
    {
        return $this->opinion;
    }

    /**
     * @param mixed $opinion
     * @return Valoracion
     */
    public function setOpinion($opinion)
    {
        $this->opinion = $opinion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPuntuacion()
    {
        return $this->puntuacion;
    }

    /**
     * @param mixed $puntuacion
     * @return Valoracion
     */
    public function setPuntuacion($puntuacion)
    {
        $this->puntuacion = $puntuacion;
        return $this;
    }


}

