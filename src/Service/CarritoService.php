<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CarritoService
{
    public function __construct(private RequestStack $requestStack,)
    {

    }

    public function getCountItems(): int
    {
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito', []);
        $count = 0;

        foreach ($carrito as $item) {
            $count += $item['cantidad'];
        }

        return $count;
    }
}
