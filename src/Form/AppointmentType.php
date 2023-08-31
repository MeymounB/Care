<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre",
                'error_bubbling' => true,
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                "label" => "Description",
                'required' => true,
                'attr' => [
                    'rows' => 10,
                ],
                'error_bubbling' => true,

            ])

            ->add('plannedAt', DateTimeType::class, [
                'label' => "Date souhaitée",
                'widget' => 'single_text',
                'html5' => false,
                'required' => true,
                'format' => 'yyyy-MM-dd HH:mm',
                'error_bubbling' => true,
                'constraints' => [
                    new Range([
                        'min' => 'now',
                        'minMessage' => 'La date doit être supérieure à la date actuelle',
                    ])
                ]
            ])

            ->add('isPresential', ChoiceType::class, [
                'choices' => [
                    'Présentiel' => true,
                    'Distanciel' => false,
                ],
                // 'expanded' => true,
                'label' => 'Vous préférez un rendez-vous :',
                'error_bubbling' => true,
                'required' => true
            ])

            // ->add('address', TextType::class, [
            //     'required' => false,
            //     "label" => "Adresse"
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
