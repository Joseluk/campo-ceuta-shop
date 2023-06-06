<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\MensajeRepository;
use App\Service\CarritoService;
use App\Service\RoleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class StockController extends AbstractController
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
    #[Route('/stock/', name: 'app_stock_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $stocks = $entityManager
            ->getRepository(Stock::class)
            ->findAll();

        return $this->render('stock/index.html.twig', [
            'stocks' => $stocks,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/stock/nuevo', name: 'app_stock_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/stock/{id}', name: 'app_stock_show', methods: ['GET'])]
    public function show(Stock $stock): Response
    {
        return $this->render('stock/show.html.twig', [
            'stock' => $stock,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/stock/{id}/editar', name: 'app_stock_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/stock/{id}', name: 'app_stock_delete', methods: ['POST'])]
    public function delete(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/stock-resumen', name: 'app_stock_resumen', methods: ['GET'])]
    public function resumen(EntityManagerInterface $entityManager): Response
    {
        $productoRepository = $entityManager->getRepository(Producto::class);

        $proveedoresProductosStock = $productoRepository->createQueryBuilder('p')
            ->join('p.proveedor', 'prov')
            ->join('p.stock', 's')
            ->select('prov.id as proveedor_id, prov.nombre as proveedor_nombre, p.id as producto_id, p.nombre as producto_nombre, s.cantidad as stock_cantidad')
            ->getQuery()
            ->getResult();

        return $this->render('stock/resumen.html.twig', [
            'proveedoresProductosStock' => $proveedoresProductosStock,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }


}
