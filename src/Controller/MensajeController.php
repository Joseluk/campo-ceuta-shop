<?php

namespace App\Controller;

use App\Entity\Mensaje;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use App\Repository\MensajeRepository;
use App\Service\CarritoService;
use App\Service\RoleService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/mensajes")
 */
class MensajeController extends AbstractController
{
    protected UserInterface $user;
    protected array $accesibleRoutes;

    public function __construct(
        private Security $security,
        private RoleService $roleService,
        private CarritoService $carritoService,
        private UsuarioRepository $usuarioRepository,
        private MensajeRepository $mensajeRepository
    ) {
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }

    /**
     * @Route("/", name="app_mensajes", methods={"GET","POST"})
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $emisor = $this->getUser();
        $mensajes = $em->getRepository(Mensaje::class)->findBy(
            ['receptor' => $emisor],
            ['fecha_envio' => 'DESC'] // (más reciente primero)
        );

        $rolesEmisor = $emisor->getRoles();

        // Primero marcar todos los mensajes como leídos
        $this->mensajeRepository->markMessagesAsRead($emisor->getId());
        $usuarios = [];

        if(in_array('ROLE_ADMIN', $rolesEmisor)){
            $usuarios = $this->usuarioRepository->findByRoles(['ROLE_USER', 'ROLE_JEFE']);
        }elseif(in_array('ROLE_JEFE', $rolesEmisor)){
            $usuarios = $this->usuarioRepository->findByRole('ROLE_ADMIN');
        }

        if ($request->isMethod('POST')) {
            if (!in_array('ROLE_ADMIN', $emisor->getRoles()) && !in_array('ROLE_JEFE', $emisor->getRoles())) {
                throw new AccessDeniedException('No tienes permiso para enviar mensajes');
            }

            $receptorId = $request->request->get('receptor');
            $contenido = $request->request->get('contenido');

            $receptor = $em->getRepository(Usuario::class)->find($receptorId);
            if (!$receptor) {
                throw $this->createNotFoundException('No se encontró al usuario receptor');
            }

            if (!in_array('ROLE_USER', $receptor->getRoles()) && !in_array('ROLE_JEFE', $receptor->getRoles()) && $emisor->getRoles() == ['ROLE_ADMIN']) {
                throw new AccessDeniedException('No puedes enviar mensajes a este usuario');
            }

            $mensaje = new Mensaje();
            $mensaje->setEmisor($emisor);
            $mensaje->setReceptor($receptor);
            $mensaje->setContenido($contenido);
            $mensaje->setLeido(false);
            $mensaje->setFechaEnvio(new \DateTime());

            $em->persist($mensaje);
            $em->flush();

            $this->addFlash(
                'success',
                'El mensaje se ha enviado con éxito!'
            );

            return $this->redirectToRoute('app_mensajes');
        }

        return $this->render('mensajes/index.html.twig', [
            'mensajes' => $mensajes,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'usuariosReceptores' => $usuarios,
            'noLeidosCount' => 0,
        ]);
    }
}

