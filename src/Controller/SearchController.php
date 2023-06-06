<?php

namespace App\Controller;

use App\Repository\MensajeRepository;
use App\Service\ProductosService;
use App\Service\RoleService;
use App\Service\CarritoService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class SearchController extends AbstractController
{
    protected UserInterface $user;
    protected array $accesibleRoutes;

    public function __construct(
        private Security $security,
        private RoleService $roleService,
        private CarritoService $carritoService,
        private ProductosService $productosService,
        private MensajeRepository $mensajeRepository
    ) {
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }

    /**
     * @Route("/search", name="app_search", methods={"GET"})
     */
    public function search(Request $request): Response
    {
        $query = $request->query->get('q', '');
        $productos = $this->productosService->buscarProductos($query);

        return $this->render('search/results.html.twig', [
            'productos' => $productos,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }
}
