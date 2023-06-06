<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Entity\Valoracion;
use App\Form\ProductoType;
use App\Repository\MensajeRepository;
use App\Service\CarritoService;
use App\Service\RoleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route('/productos')]
class ProductoController extends AbstractController
{
    private Security $security;
    private RoleService $roleService;
    protected $user;
    protected array $accesibleRoutes;

    public function __construct(
        Security $security,
        RoleService $roleService,
        private CarritoService $carritoService,
        private MensajeRepository $mensajeRepository,
    )
    {
        $this->security = $security;
        $this->roleService = $roleService;
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }

    #[Route('/', name: 'app_producto_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $productos = $entityManager
            ->getRepository(Producto::class)
            ->findBy([], ['id' => 'ASC']);

        return $this->render('producto/index.html.twig', [
            'productos' => $productos,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/nuevo', name: 'app_producto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($producto);
            $entityManager->flush();

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('producto/new.html.twig', [
            'producto' => $producto,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}', name: 'app_producto_show', methods: ['GET'])]
    public function show(Producto $producto, EntityManagerInterface $entityManager): Response
    {
        // Recuperamos las valoraciones del producto
        $valoraciones = $entityManager->getRepository(Valoracion::class)->findBy([
            'producto' => $producto,
        ]);

        // Calculamos la media de las puntuaciones
        $mediaPuntuacion = $entityManager->createQueryBuilder()
            ->select('AVG(valoracion.puntuacion) as media')
            ->from(Valoracion::class, 'valoracion')
            ->where('valoracion.producto = :producto')
            ->setParameter('producto', $producto)
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('producto/show.html.twig', [
            'producto' => $producto,
            'valoraciones' => $valoraciones,
            'valoracionProducto' => $mediaPuntuacion,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}/editar', name: 'app_producto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Producto $producto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('producto/edit.html.twig', [
            'producto' => $producto,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}', name: 'app_producto_delete', methods: ['POST'])]
    public function delete(Request $request, Producto $producto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->request->get('_token'))) {
            $entityManager->remove($producto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/valoracion", name="app_producto_valoracion", methods={"POST"})
     */
    public function valoracion($id, Request $request, EntityManagerInterface $entityManager)
    {
        $producto = $entityManager->getRepository(Producto::class)->find($id);
        $usuario = $this->getUser();

        // Verificamos si el usuario ya ha realizado una valoracion
        $valoracionesUsuario = $entityManager->getRepository(Valoracion::class)->findBy([
            'usuario' => $usuario,
            'producto' => $producto,
        ]);

        if (count($valoracionesUsuario) >= 1) {
            $this->addFlash('warning', 'No puedes realizar más de dos valoraciones para el mismo producto');
            return $this->redirectToRoute('app_producto_show', ['id' => $id]);
        }

        $valoracion = new Valoracion();
        $valoracion->setUsuario($usuario);
        $valoracion->setProducto($producto);
        $valoracion->setOpinion($request->request->get('comentario'));
        $valoracion->setPuntuacion($request->request->get('valoracion'));

        $entityManager->persist($valoracion);
        $entityManager->flush();

        // Redirecciona a la página del producto después de enviar la valoración
        return $this->redirectToRoute('app_producto_show', ['id' => $id]);
    }

    /**
     * @Route("/{id_producto}/valoracion/{id_valoracion}/editar", name="app_producto_valoracion_edit", methods={"GET","POST"})
     */
    public function editValoracion($id_producto, $id_valoracion, Request $request, EntityManagerInterface $entityManager)
    {
        $producto = $entityManager->getRepository(Producto::class)->find($id_producto);
        $usuario = $this->getUser();

        // Verificamos si la valoracion pertenece al usuario actual y al producto
        $valoracion = $entityManager->getRepository(Valoracion::class)->findOneBy([
            'usuario' => $usuario,
            'producto' => $producto,
            'id' => $id_valoracion,
        ]);

        if (!$valoracion) {
            $this->addFlash('warning', 'No puedes editar una valoración que no te pertenece');
            return $this->redirectToRoute('app_producto_show', ['id' => $id_producto]);
        }

        if ($request->isMethod('POST')) {
            $valoracion->setOpinion($request->request->get('comentario'));
            $valoracion->setPuntuacion($request->request->get('valoracion'));

            $entityManager->flush();

            $this->addFlash('success', 'Tu valoración ha sido actualizada');
            return $this->redirectToRoute('app_producto_show', ['id' => $id_producto]);
        }

        return $this->render('producto/edit_valoracion.html.twig', [
            'producto' => $producto,
            'valoracion' => $valoracion,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }



}
