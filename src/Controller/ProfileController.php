<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\ProfileFormType;
use App\Repository\MensajeRepository;
use App\Service\CarritoService;
use App\Service\ProductosService;
use App\Service\RoleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    protected $user;
    protected array $accesibleRoutes;

    public function __construct(
        private \Symfony\Bundle\SecurityBundle\Security $security,
        private RouterInterface                         $router,
        private RoleService                             $roleService,
        private ProductosService                        $productosService,
        private MensajeRepository                       $mensajeRepository,
        private CarritoService                          $carritoService,
    )
    {
        $this->user = $this->security->getUser();
        $this->accesibleRoutes = $this->roleService->getAccessibleRoutes($this->user);
    }

    /**
     * @Route("/perfil", name="app_perfil")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        /** @var \App\Entity\Usuario $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('plainPassword')->getData()) {
                $user->setContrasena(
                    $passwordEncoder->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }


            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                '¡Tu perfil se ha actualizado con éxito!'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/perfil.html.twig', [
            'profileForm' => $form->createView(),
            'accesibleRoutes' => $this->accesibleRoutes,
            'user' => $this->user,
            'carritoCount' => $this->carritoService->getCountItems(),
            'noLeidosCount' => $this->mensajeRepository->countUnreadMessagesByUser($this->user->getId()),
        ]);
    }
}

