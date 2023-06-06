<?php

namespace App\Controller;

use App\Entity\Pedido;
use App\Entity\PedidoDetalle;
use App\Entity\Producto;
use App\Entity\Usuario;
use App\Form\PedidoType;
use App\Repository\MensajeRepository;
use App\Service\CarritoService;
use App\Service\ProductosService;
use App\Service\RoleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class PedidoController extends AbstractController
{
    protected $user;
    protected array $accesibleRoutes;

    public function __construct(
        private Security $security,
        private RouterInterface $router,
        private RoleService $roleService,
        private MensajeRepository $mensajeRepository,
        private CarritoService $carritoService,
    )
    {
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }

    #[Route('/pedido/', name: 'app_pedido_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $pedidos = $entityManager
            ->getRepository(Pedido::class)
            ->findAll();

        return $this->render('pedido/index.html.twig', [
            'pedidos' => $pedidos,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/pedido/resumen', name: 'app_pedido_resumen', methods: ['GET'])]
    public function resumen(EntityManagerInterface $entityManager): Response
    {
        $pedidoRepository = $entityManager->getRepository(Pedido::class);
        $pedidoDetalleRepository = $entityManager->getRepository(PedidoDetalle::class);
        $productoRepository = $entityManager->getRepository(Producto::class);
        $pedidos = $entityManager
            ->getRepository(Pedido::class)
            ->findAll();

        $numeroDePedidos = $pedidoRepository->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $cantidadFacturacion = $pedidoDetalleRepository->createQueryBuilder('pd')
            ->select('sum(pd.cantidad * pd.precio)')
            ->getQuery()
            ->getSingleScalarResult();

        $productoMasVendido = $pedidoDetalleRepository->createQueryBuilder('pd')
            ->select('IDENTITY(pd.producto) as producto_id, sum(pd.cantidad) as cant')
            ->groupBy('pd.producto')
            ->orderBy('cant', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $productosVendidos = $pedidoDetalleRepository->createQueryBuilder('pd')
            ->select('IDENTITY(pd.producto) as producto_id, sum(pd.cantidad) as cant')
            ->groupBy('pd.producto')
            ->getQuery()
            ->getResult();

        $usuarioMasRentable = $pedidoDetalleRepository->createQueryBuilder('pd')
            ->join('pd.pedido', 'p')
            ->join('p.usuario', 'u')
            ->select('u.nombre as usuario_nombre, sum(pd.cantidad * pd.precio) as total')
            ->groupBy('u.nombre')
            ->orderBy('total', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

//        dd($usuarioMasRentable);

        return $this->render('pedido/resumen.html.twig', [
            'pedidos' => $pedidos,
            'numeroDePedidos' => $numeroDePedidos,
            'cantidadFacturacion' => $cantidadFacturacion,
            'productoMasVendido' => $productoRepository->find($productoMasVendido['producto_id']),
            'productosVendidos' => array_column($productosVendidos, 'producto'),
            'usuarioMasRentable' => $usuarioMasRentable['usuario_nombre'],
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }


    #[Route('/mis-pedidos', name: 'app_mis_pedido_index', methods: ['GET'])]
    public function pedidosUsuario(EntityManagerInterface $entityManager): Response
    {
        $pedidos = $entityManager
            ->getRepository(Pedido::class)
            ->findBy(['usuario' => $this->user]);

        return $this->render('pedido/index_usuario.html.twig', [
            'pedidos' => $pedidos,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
            'misPedidos' => true,
        ]);
    }


    #[Route('/pedido/new', name: 'app_pedido_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pedido = new Pedido();
        $form = $this->createForm(PedidoType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pedido);
            $entityManager->flush();

            return $this->redirectToRoute('app_pedido_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pedido/new.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/pedido/{id}', name: 'app_pedido_show', methods: ['GET'])]
    public function show(Pedido $pedido): Response
    {
        return $this->render('pedido/show.html.twig', [
            'pedido' => $pedido,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/pedido/{id}/edit', name: 'app_pedido_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pedido $pedido, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PedidoType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pedido_index', [], Response::HTTP_SEE_OTHER);
        }

        $pedidoDetalles = $entityManager
            ->getRepository(PedidoDetalle::class)
            ->findBy(['pedido' => $pedido]);

        return $this->renderForm('pedido/edit.html.twig', [
            'pedido' => $pedido,
            'pedido_detalles' => $pedidoDetalles,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/pedido/{id}', name: 'app_pedido_delete', methods: ['POST'])]
    public function delete(Request $request, Pedido $pedido, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pedido->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pedido);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pedido_index', [], Response::HTTP_SEE_OTHER);
    }
}
