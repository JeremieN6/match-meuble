<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AnnonceImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('images', FileType::class, [
            'label' => 'Images',
            'mapped' => false,
            'multiple' => true,
            'required' => false,
            'constraints' => [
                new Assert\All([
                    new Assert\Image([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
                        'mimeTypesMessage' => 'Formats acceptés: JPEG, PNG, WEBP, GIF',
                        'maxSizeMessage' => 'Taille maximale 5 Mo',
                    ])
                ])
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
