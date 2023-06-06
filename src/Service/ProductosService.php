<?php

// src/Service/ProductosService.php

namespace App\Service;

use App\Entity\Producto;
use App\Entity\Stock;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categoria;

class ProductosService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function obtenerCategoriasConProductos()
    {
        $categorias = $this->entityManager->getRepository(Categoria::class)->findAll();

        foreach ($categorias as $categoria) {
            $productos = $categoria->getProductos();
            $productos->initialize();

            $productosConStock = new ArrayCollection();

            foreach ($productos as $producto) {
                $stock = $producto->getStock();
                if ($stock !== null && $stock->getCantidad() > 0) { // Revisamos el stock
                    $productosConStock->add($producto);
                }
            }

            // Reemplaza el valor de la propiedad 'productos' con nuestra nueva colección
            $reflectionProperty = new \ReflectionProperty(Categoria::class, 'productos');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($categoria, $productosConStock);
        }

        return $categorias;
    }

    public function buscarProductos(string $query): array
    {
        $productosRepository = $this->entityManager->getRepository(Producto::class);

        $productos = $productosRepository->createQueryBuilder('p')
            ->where('LOWER(p.nombre) LIKE LOWER(:query)')
            ->setParameter('query', '%' . strtolower($query) . '%')
            ->getQuery()
            ->getResult();

        foreach ($productos as $producto) {
            if ($producto->getStock() === null) {
                $stockVacio = new Stock();
                $stockVacio->setCantidad(0);
                $stockVacio->setPrecio(0);
                $producto->setStock($stockVacio);
            }
        }

        return $productos;
    }


}

