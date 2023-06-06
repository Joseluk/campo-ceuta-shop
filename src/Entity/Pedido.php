<?php

namespace App\Entity;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pedido")
 */
class Pedido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string")
     */
    private $dirEntrega;

    /**
     * @ORM\OneToMany(targetEntity=PedidoDetalle::class, mappedBy="pedido", orphanRemoval=true, cascade={"persist"})
     */
    private $pedidoDetalles;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Pedido
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
     * @return Pedido
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirEntrega()
    {
        return $this->dirEntrega;
    }

    /**
     * @param mixed $dirEntrega
     * @return Pedido
     */
    public function setDirEntrega($dirEntrega)
    {
        $this->dirEntrega = $dirEntrega;
        return $this;
    }

    /**
     * @return Collection|PedidoDetalle[]
     */
    public function getPedidoDetalles(): ?Collection
    {
        return $this->pedidoDetalles;
    }

    public function addPedidoDetalle(PedidoDetalle $pedidoDetalle): self
    {
        if (!$this->pedidoDetalles->contains($pedidoDetalle)) {
            $this->pedidoDetalles[] = $pedidoDetalle;
            $pedidoDetalle->setPedido($this);
        }

        return $this;
    }

    public function removePedidoDetalle(PedidoDetalle $pedidoDetalle): self
    {
        if ($this->pedidoDetalles->removeElement($pedidoDetalle)) {
            if ($pedidoDetalle->getPedido() === $this) {
                $pedidoDetalle->setPedido(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return "Pedido $this->id de $this->usuario";
    }
}


