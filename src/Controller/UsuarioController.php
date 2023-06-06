<?php

namespace App\Controller;


use App\Entity\Usuario;
use App\Repository\MensajeRepository;
use App\Service\CarritoService;
use App\Service\RoleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends AbstractController
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
    #[Route('/usuarios/resumen', name: 'app_usuario_resumen', methods: ['GET'])]
    public function resumen(EntityManagerInterface $entityManager): Response
    {
        $usuarioRepository = $entityManager->getRepository(Usuario::class);
        $usuarios = $usuarioRepository->findAll();

        return $this->render('usuario/resumen.html.twig', [
            'usuarios' => $usuarios,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

}
