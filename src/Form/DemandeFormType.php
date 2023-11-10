<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class DemandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', TextType::class, [
            'label' => 'Titre',
            'attr' => [
                'placeholder' => 'Saisissez un titre pour votre offre',
            ],
            'constraints' => [
                new NotNull([
                    'message' => 'Veuillez remplir ce champs pour valider',
                ]),
            ],
        ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez brièvement vos compétences et vos motivations. 🙂'
                ]
            ])
            ->add('zoneAction', IntegerType::class, [
                'label' => 'Zone d\'action',
                'attr' => [
                    'placeholder' => 'De combien de Km autour de votre ville êtes vous en action ?'
                ],
            ])
            ->add('disponibilite', ChoiceType::class, [
                'choices' => [
                    'Immédiatement' => 'immediatement',
                    'Disponible dans 2 à 3 jours' => '2_3_jours',
                    'Disponible dans 1 semaine à 2 semaines' => '1_2_semaine',
                    'Disponible dans 1 mois et plus' => '1_mois_et_plus',
                ],
                'label' => 'Disponobilité',
            ])
            ->add('salaire', IntegerType::class, [
                'label' => 'Tarif à l\'heure',
                'attr' => [
                    'placeholder' => 'Quel est votre tarif pour 1 heure de montage ?'
                ],
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
