<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de l\'article'
                ]
            ])
            ->add('prix', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prix de l\'article'
                ]
            ])
            ->add('description', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
