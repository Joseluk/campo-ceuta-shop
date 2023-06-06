<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    public function findByRoles(array $roles)
    {
        $usuarios = $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();

        return array_filter($usuarios, function($usuario) use ($roles) {
            $usuarioRoles = $usuario->getRoles();
//            echo "<pre>";
//            print_r($usuarioRoles);
//            print_r($roles);
//            print_r(count(array_intersect($usuarioRoles, $roles)) > 0);
            return count(array_intersect($usuarioRoles, $roles)) > 0;
        });
    }



    public function findByRole(string $role)
    {
        return $this->findByRoles([$role]);
    }
}
