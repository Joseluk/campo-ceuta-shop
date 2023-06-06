<?php

namespace App\Controller;

use App\Entity\Pedido;
use App\Entity\PedidoDetalle;
use App\Entity\Producto;
use App\Repository\MensajeRepository;
use App\Service\CarritoService;
use App\Service\RoleService;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/carrito')]
class CarritoController extends AbstractController
{
    protected UserInterface $user;
    protected array $accesibleRoutes;

    public function __construct(
        private RequestStack $requestStack,
        private Security $security,
        private RoleService $roleService,
        private CarritoService $carritoService,
        private MensajeRepository $mensajeRepository,
    ) {
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }

    #[Route('/', name: 'carrito_index')]
    public function index(): Response
    {
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito', []);
        $total = $session->get('total');


        return $this->render('carrito/index.html.twig', [
            'items' => $carrito,
            'total' => $total,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/add/{id}', name: 'carrito_add')]
    public function add(Producto $producto): Response
    {
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito', []);
        $id = $producto->getId();
        $precio = $producto->getStock()->getPrecio();  // precio por unidad

        if (!isset($carrito[$id])) {
            $carrito[$id] = [
                'producto' => $producto,
                'cantidad' => 0,
                'precio' => $precio,
                'precio_final' => 0,
            ];
        }

        $carrito[$id]['cantidad']++;
        $carrito[$id]['precio_final'] = $carrito[$id]['cantidad'] * $precio; // Precio final por cada artículo, precio * cantidad
        $session->set('carrito', $carrito); // Guardamos el carrito

        // Calculo del total
        $total = 0;
        foreach ($carrito as $producto) {
            $total += $producto['precio_final'];
        }
        $session->set('total', $total);


        return $this->redirectToRoute('carrito_index');
    }

    #[Route('/borrar/{id}', name: 'carrito_remove')]
    public function remove(Producto $producto): Response
    {
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito', []);
        $id = $producto->getId();

        if (isset($carrito[$id])) {
            $precio = $carrito[$id]['precio'];

            if ($carrito[$id]['cantidad'] > 1) {
                $carrito[$id]['cantidad']--;
                $carrito[$id]['precio_final'] -= $precio;
            } else {
                $carrito[$id]['precio_final'] = 0;
                unset($carrito[$id]);
            }
        }

        $session->set('carrito', $carrito);

        // Calculo del total
        $total = 0;
        foreach ($carrito as $producto) {
            $total += $producto['precio_final'];
        }
        $session->set('total', $total);

        return $this->redirectToRoute('carrito_index');
    }


    #[Route('/vaciar', name: 'carrito_clear')]
    public function clear(): Response
    {
        $session = $this->requestStack->getSession();
        $session->set('carrito', []);

        return $this->redirectToRoute('carrito_index');
    }

    #[Route('/pagar', name: 'carrito_confirmar', methods: ['GET', 'POST'])]
    public function confirmar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('dirEntrega', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Guarda la direccion en la sesion
            $session = $this->requestStack->getSession();
            $session->set('dirEntrega', $data['dirEntrega']);

            return $this->redirectToRoute('carrito_comprar');
        }

        return $this->render('carrito/confirmar.html.twig', [
            'form' => $form->createView(),
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/comprar', name: 'carrito_comprar', methods: ['GET'])]
    public function comprar(EntityManagerInterface $entityManager): Response
    {
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito');
        $dirEntrega = $session->get('dirEntrega');

        // Crear una nueva entidad Pedido
        $pedido = new Pedido();
        $pedido->setUsuario($this->user);
        $pedido->setDirEntrega($dirEntrega);

        $entityManager->persist($pedido);
        // Para cada elemento en el carrito, crear una entidad DetallePedido
        foreach ($carrito as $itemId => $itemCarrito) {

            $item = $entityManager->getRepository(Producto::class)->find($itemId);

            if (!$item) {
                continue; // si no se encuentra el producto, omitir
            }

            // Obtenemos el precio y la cantidad en stock a través de la relación con la entidad Stock
            $stock = $item->getStock();
            if ($stock === null || $stock->getCantidad() < $itemCarrito['cantidad']) {
                throw new \Exception("No hay stock disponible para alguno de los artículos seleccionados");
            }
            $precio = $stock->getPrecio();

            // Reducimos la cantidad de stock
            $stock->setCantidad($stock->getCantidad() - $itemCarrito['cantidad']);

            $detallePedido = new PedidoDetalle();
            $detallePedido->setPedido($pedido);
            $detallePedido->setProducto($item);
            $detallePedido->setCantidad($itemCarrito['cantidad']);
            $detallePedido->setPrecio($precio * $itemCarrito['cantidad']); // precio total = precio unitario * cantidad

            $entityManager->persist($detallePedido);
            $entityManager->persist($stock);
        }

        $entityManager->flush();

        // Vaciar el carrito
        $session->set('carrito', []);

        // redireccionar a la página de pedidos del usuario
        return $this->redirectToRoute('app_mis_pedido_index', ['id' => $pedido->getId()]);
    }


}

