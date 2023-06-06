<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="mensaje")
 */
class Mensaje
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="emisor", referencedColumnName="id")
     */
    private $emisor;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="receptor", referencedColumnName="id")
     */
    private $receptor;

    /**
     * @ORM\Column(type="text")
     */
    private $contenido;

    /**
     * @ORM\Column(type="boolean")
     */
    private $leido;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_envio;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Mensaje
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmisor()
    {
        return $this->emisor;
    }

    /**
     * @param mixed $emisor
     * @return Mensaje
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReceptor()
    {
        return $this->receptor;
    }

    /**
     * @param mixed $receptor
     * @return Mensaje
     */
    public function setReceptor($receptor)
    {
        $this->receptor = $receptor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * @param mixed $contenido
     * @return Mensaje
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
        return $this;
    }



    public function isLeido(): bool
    {
        return $this->leido;
    }

    public function setLeido(bool $leido): void
    {
        $this->leido = $leido;
    }

    public function fecha_envio(): \DateTimeInterface
    {
        return $this->fecha_envio;
    }

    public function getFechaEnvio(): \DateTimeInterface
    {
        return $this->fecha_envio;
    }

    public function setFechaEnvio(\DateTimeInterface $fecha_envio): void
    {
        $this->fecha_envio = $fecha_envio;
    }

}

