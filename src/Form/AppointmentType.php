<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
            ])
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
            ])
            ->add('updatedAt')
            ->add('plannedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
            ])
            ->add('isPresential', ChoiceType::class, [
                'choices' => [
                    'Présentiel' => true,
                    'Distanciel' => false,
                ],
                'expanded' => true,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($data->getIsPresential() === true) {
                    $form->add('adress', TextType::class);
                } else if ($data->getIsPresential() === false) {
                    $form->add('link', TextType::class);
                }
            })
            ->add('adress', TextType::class)
            ->add('link', TextType::class)
            ->add('plants')
            ->add('status');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
