<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            // TODO : Add JS Librairie Calendar : field is actually a text field
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
            ])
            // Create the form field dynamically on server side
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if (true === $data->getIsPresential()) {
                    $form->add('address', TextType::class);
                } elseif (false === $data->getIsPresential()) {
                    $form->add('link', TextType::class);
                }
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if (isset($data['isPresential'])) {
                    if (true === $data['isPresential']) {
                        $form->add('address', TextType::class);
                    } else {
                        $form->add('link', TextType::class);
                    }
                }
            });
        // ->add('address', TextType::class, [
        //     'attr' => ['class' => 'hidden'],
        // ])
        // ->add('link', TextType::class, [
        //     'attr' => ['class' => 'hidden'],
        // ]);
        // ->add('plants')
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
