<?php

namespace App\Form;

use App\Entity\Documents;
use App\Entity\Dossier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cni',FileType::class, ["label" => "Carte D'identité (PDF)",
                "required" =>false,
                "attr" => ["class" => "form-control"]])
            ->add('justif_dom', FileType::class, ["label" => "Justificatif de Domicile (PDF)",
                "required" =>false,
                "attr" => ["class" => "form-control"]])
            ->add('userResponse', TextareaType::class,[
                "label" => "Pourquoi le choisir ? Réponses sentimentales seulement autorisé",
                "attr" => ["class" => "form-group"]
            ])
            ->add("Valider", SubmitType::class,[
                "attr" => ["class" => "btn btn-primary"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}
