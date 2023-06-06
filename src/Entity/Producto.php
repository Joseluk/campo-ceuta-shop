<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 * @ORM\Table(name="producto")
 */
#[Vich\Uploadable]
class Producto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string")
     */
    private $marca;

    /**
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumn(name="id_proveedor", referencedColumnName="id")
     */
    private $proveedor;

    /**
     * @ORM\Column(type="string")
     */
    private $especificacion;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="id_categoria", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @ORM\OneToOne(targetEntity="Stock", mappedBy="producto")
     */
    private $stock;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt; // Para saber cuando se actualiza la imagen

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagen;

    #[Vich\UploadableField(mapping: 'product_image', fileNameProperty: 'imagen')]
    private $imageFile;


    /**
     * @param mixed $id
     * @return Producto
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     * @return Producto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * @param mixed $marca
     * @return Producto
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * @param mixed $proveedor
     * @return Producto
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEspecificacion()
    {
        return $this->especificacion;
    }

    /**
     * @param mixed $especificacion
     * @return Producto
     */
    public function setEspecificacion($especificacion)
    {
        $this->especificacion = $especificacion;
        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->nombre;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(Stock $stock): self
    {
        if ($stock->getProducto() !== $this) {
            $stock->setProducto($this);
        }

        $this->stock = $stock;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return Producto
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }


}

