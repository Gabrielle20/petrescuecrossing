<?php

namespace App\Form;

use App\Entity\Animal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaveAnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                "label" => "Nom de l'animal",
            ])
            ->add('age',IntegerType::class,[
                "label" => "Age",
                "attr" => ["class" => "form-group"]
            ])
            ->add('type', TextType::class,[
                "label" => "Type de l'animal",
                "attr" => ["class" => "form-group"]
            ])
            ->add('couleur', TextType::class,[
                "label" => "Couleur",
                "attr" => ["class" => "form-group"]
            ])
            ->add('description', TextareaType::class,[
                "label" => "Description",
                "attr" => ["class" => "form-group"]
            ])
            ->add('date_arrive', DateType::class,[
                "label" => "Date d'arrivée",
                "attr" => ["class" => "form-group"]
            ])
            ->add("Ajouter", SubmitType::class,[
                "attr" => ["class" => "btn btn-primary"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
