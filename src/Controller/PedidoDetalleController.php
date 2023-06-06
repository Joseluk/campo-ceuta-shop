<?php

namespace App\Controller;

use App\Entity\PedidoDetalle;
use App\Form\PedidoDetalleType;
use App\Repository\MensajeRepository;
use App\Service\CarritoService;
use App\Service\RoleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pedido/detalle')]
class PedidoDetalleController extends AbstractController
{
    private Security $security;
    private RoleService $roleService;
    protected $user;
    protected array $accesibleRoutes;

    public function __construct(Security $security, RoleService $roleService, private MensajeRepository $mensajeRepository, private CarritoService $carritoService)
    {
        $this->security = $security;
        $this->roleService = $roleService;
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }

    #[Route('/', name: 'app_pedido_detalle_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $pedidoDetalles = $entityManager
            ->getRepository(PedidoDetalle::class)
            ->findAll();

        return $this->render('pedido_detalle/index.html.twig', [
            'pedido_detalles' => $pedidoDetalles,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/new', name: 'app_pedido_detalle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pedidoDetalle = new PedidoDetalle();
        $form = $this->createForm(PedidoDetalleType::class, $pedidoDetalle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pedidoDetalle);
            $entityManager->flush();

            return $this->redirectToRoute('app_pedido_detalle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pedido_detalle/new.html.twig', [
            'pedido_detalle' => $pedidoDetalle,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}', name: 'app_pedido_detalle_show', methods: ['GET'])]
    public function show(PedidoDetalle $pedidoDetalle): Response
    {
        return $this->render('pedido_detalle/show.html.twig', [
            'pedido_detalle' => $pedidoDetalle,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pedido_detalle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PedidoDetalle $pedidoDetalle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PedidoDetalleType::class, $pedidoDetalle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pedido_detalle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pedido_detalle/edit.html.twig', [
            'pedido_detalle' => $pedidoDetalle,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}', name: 'app_pedido_detalle_delete', methods: ['POST'])]
    public function delete(Request $request, PedidoDetalle $pedidoDetalle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pedidoDetalle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pedidoDetalle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pedido_detalle_index', [], Response::HTTP_SEE_OTHER);
    }
}
