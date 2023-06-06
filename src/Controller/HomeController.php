<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Repository\MensajeRepository;
use App\Service\CarritoService;
use App\Service\ProductosService;
use App\Service\RoleService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class HomeController extends AbstractController
{
    protected $user;
    protected array $accesibleRoutes;

    public function __construct(
        private Security $security,
        private RouterInterface $router,
        private RoleService $roleService,
        private ProductosService $productosService,
        private MensajeRepository $mensajeRepository,
        private CarritoService $carritoService,
    )
    {
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }

    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categoriasConProductos = $this->productosService->obtenerCategoriasConProductos();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categorias' => $categoriasConProductos,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }



}
