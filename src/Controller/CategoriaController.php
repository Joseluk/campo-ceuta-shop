<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Form\CategoriaType;
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

#[Route('/categoria')]
class CategoriaController extends AbstractController
{
    protected UserInterface $user;
    protected array $accesibleRoutes;

    public function __construct(
        private Security $security,
        private RoleService $roleService,
        private CarritoService $carritoService,
        private MensajeRepository $mensajeRepository,
    ) {
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }
    #[Route('/', name: 'app_categoria_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categorias = $entityManager
            ->getRepository(Categoria::class)
            ->findAll();

        return $this->render('categoria/index.html.twig', [
            'categorias' => $categorias,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/new', name: 'app_categoria_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorium = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorium);
            $entityManager->flush();

            return $this->redirectToRoute('app_categoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categoria/new.html.twig', [
            'categorium' => $categorium,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}', name: 'app_categoria_show', methods: ['GET'])]
    public function show(Categoria $categorium): Response
    {
        return $this->render('categoria/show.html.twig', [
            'categorium' => $categorium,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categoria_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categoria $categorium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categoria/edit.html.twig', [
            'categorium' => $categorium,
            'form' => $form,
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }

    #[Route('/{id}', name: 'app_categoria_delete', methods: ['POST'])]
    public function delete(Request $request, Categoria $categorium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorium->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categoria_index', [], Response::HTTP_SEE_OTHER);
    }
}
