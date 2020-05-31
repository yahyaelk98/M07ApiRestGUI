<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PeliculaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required'=>false))
            ->add('genero', TextType::class, array('required'=>false))
            ->add('descripcion', TextType::class, array('required'=>false))
        ;
    }

}
