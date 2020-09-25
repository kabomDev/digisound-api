<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'évènement",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Titre de l'évènement",
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Decrivez votre évènement",
                'attr' => [
                    'class' => 'tinymce',
                    'class' => 'form-control',
                    'placeholder' => "Faites une description sur l'évènement"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => "Indiquez la ville",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Indiquez la ville où l'évènement va être organisé"
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'ajoutez une image',
                'mapped' => true,
                'required' => false,
                'data_class' => null,

            ])
            ->add('capacity', NumberType::class, [
                'label' => "capacité maximal",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Indiquez la capacité maximal"
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'prix de la place',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'prix de la place'
                ]
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => "la date de début",

            ])
            ->add('endDate', DateTimeType::class, [
                // 'widget' => 'single_text',
                // 'format' => 'dd-MM-yyyy HH:ii',
                // 'html5' => false,
                'label' => "la date de fin",
                // 'attr' => [
                //     //'class' => 'form-control',
                //     'placeholder' => '01-01-2020 23:45'
                // ],
            ])
            ->add('artists', EntityType::class, [
                'class' => Artist::class,
                'label' => 'selectionnez les artistes participants',
                'multiple' => true,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
