<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', null, [
                'attr' => [
                    'rows' => 10,
                ],
            ])

            ->add('plannedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm',
            ])

            ->add('isPresential', ChoiceType::class, [
                'choices' => [
                    'Présentiel' => true,
                    'Distanciel' => false,
                ],
                'expanded' => true,
                'label' => 'Vous préférez un rendez-vous :',
            ])

            ->add('address', TextType::class, [
                'required' => false,
            ])
            ->add('link', TextType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
