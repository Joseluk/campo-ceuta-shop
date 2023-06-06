<?php

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;

class RoleService
{

    private $roleRoutes = [
        'ROLE_USER' => [
            ['nombre' => 'Tienda', 'ruta' => 'app_home', 'icon' => 'fa-shop'],
            ['nombre' => 'Mis Pedidos', 'ruta' => 'app_mis_pedido_index', 'icon' => 'fa-cubes'],
            ['nombre' => 'Mi Cuenta', 'ruta' => 'app_perfil', 'icon' => 'fa-user'],
            ['nombre' => 'Bandeja de Entrada', 'ruta' => 'app_mensajes', 'icon' => 'fa-inbox'],
        ],
        'ROLE_ADMIN' => [
            ['nombre' => 'Tienda', 'ruta' => 'app_home', 'icon' => 'fa-shop'],
            ['nombre' => 'Gestión de Productos', 'ruta' => 'app_producto_index', 'icon' => 'fa-clipboard-list'],
            ['nombre' => 'Gestión de Pedidos', 'ruta' => 'app_pedido_index', 'icon' => 'fa-shopping-bag'],
            ['nombre' => 'Gestión de Categorias', 'ruta' => 'app_categoria_index', 'icon' => 'fa-bars'],
            ['nombre' => 'Gestión de Stock', 'ruta' => 'app_stock_index', 'icon' => 'fa-warehouse'],
            ['nombre' => 'Mensajes', 'ruta' => 'app_mensajes', 'icon' => 'fa-envelope'],
        ],
        'ROLE_JEFE' => [
            ['nombre' => 'Tienda', 'ruta' => 'app_home', 'icon' => 'fa-shop'],
            ['nombre' => 'Gestión de Proveedores', 'ruta' => 'app_proveedor_index', 'icon' => 'fa-truck-fast'],
            ['nombre' => 'Resumen de Pedidos', 'ruta' => 'app_pedido_resumen', 'icon' => 'fa-clipboard-list'],
            ['nombre' => 'Resumen de Stock', 'ruta' => 'app_stock_resumen', 'icon' => 'fa-chart-bar'],
            ['nombre' => 'Resumen de Registros', 'ruta' => 'app_usuario_resumen', 'icon' => 'fa-file-alt'],
            ['nombre' => 'Mensajes', 'ruta' => 'app_mensajes', 'icon' => 'fa-envelope'],
        ]
    ];

    public function getAccessibleRoutesByRoles(array $roles): array
    {
        $routes = [];

        foreach ($roles as $role) {
            if (array_key_exists($role, $this->roleRoutes)) {
                $routes = array_merge($routes, $this->roleRoutes[$role]);
            } else {
                throw new \Exception("Rol no definido: $role");
            }
        }

        // remove duplicate routes
        $routes = $this->unique_multidim_array($routes, 'ruta');

        return $routes;
    }

    public function getAccessibleRoutes(UserInterface $user): array
    {
        $roles = $user->getRoles();

        return $this->getAccessibleRoutesByRoles($roles);
    }

    // Función personalizada para tener array dimensionales con una key dada unica, en nuestro caso 'ruta'
    function unique_multidim_array($array, $key) {
        $temp_array = [];
        $i = 0;
        $key_array = [];

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}

