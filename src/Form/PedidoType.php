<?php

namespace App\Form;

use App\Entity\Pedido;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PedidoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dirEntrega')
            ->add('usuario')
            ->add('pedidoDetalles', CollectionType::class, [
                'entry_type' => PedidoDetalleType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Detalles de pedido',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pedido::class,
        ]);
    }
}

