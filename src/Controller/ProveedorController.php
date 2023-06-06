<?php

namespace App\Controller;

use App\Entity\Proveedor;
use App\Form\ProveedorType;
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

#[Route('/proveedores')]
class ProveedorController extends AbstractController
{
    protected UserInterface $user;
    protected array $accesibleRoutes;

    public function __construct(
        private Security $security,
        private RoleService $roleService,
        private CarritoService $carritoService,
        private MensajeRepository $mensajeRepository
    ) {
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }

    #[Route('/', name: 'app_proveedor_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $proveedors = $entityManager
            ->getRepository(Proveedor::class)
            ->findAll();

        return $this->render('proveedor/index.html.twig', [
            'proveedors' => $proveedors,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/new', name: 'app_proveedor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $proveedor = new Proveedor();
        $form = $this->createForm(ProveedorType::class, $proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($proveedor);
            $entityManager->flush();

            return $this->redirectToRoute('app_proveedor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('proveedor/new.html.twig', [
            'proveedor' => $proveedor,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}', name: 'app_proveedor_show', methods: ['GET'])]
    public function show(Proveedor $proveedor): Response
    {
        return $this->render('proveedor/show.html.twig', [
            'proveedor' => $proveedor,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_proveedor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Proveedor $proveedor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProveedorType::class, $proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_proveedor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('proveedor/edit.html.twig', [
            'proveedor' => $proveedor,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}', name: 'app_proveedor_delete', methods: ['POST'])]
    public function delete(Request $request, Proveedor $proveedor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proveedor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($proveedor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_proveedor_index', [], Response::HTTP_SEE_OTHER);
    }
}
