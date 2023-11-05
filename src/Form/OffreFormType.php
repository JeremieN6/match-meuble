<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'attr' => [
                'placeholder' => 'Décrivez brièvement ce que le monteur de meuble devra faire.'
            ],
        ])
        ->add('localisation', TextType::class, [
            'label' => 'Lieu',
            'attr' => [
                'placeholder' => 'Quel est l\'adresse où le montage sera effectué ? (Les détails se finaliseront en conversation privée)'
            ],
        ])
        ->add('remuneration', IntegerType::class, [
            'label' => 'Rémunération',
            'attr' => [
                'placeholder' => 'Combien rémunérez-vous pour cette session de montage ?'
            ],
        ])
        ->add('dateDebutMontage', DateType::class, [
            'label' => 'Date de début',
        ])
        ->add('dateFinMontage', DateType::class, [
            'label' => 'Date de fin',
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Valider',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
