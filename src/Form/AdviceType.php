<?php

namespace App\Form;

use App\Entity\Plant;
use App\Entity\Advice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdviceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'error_bubbling' => true,
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 10,
                ],
                'error_bubbling' => true,
                'required' => true
            ])
            ->add('isPublic', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'label' => 'Voulez-vous rendre votre demande publique ?',
                'error_bubbling' => true,
                'required' => true
            ])
            ->add('plants', EntityType::class, [
                'class' => Plant::class,
                'label' => 'Plantes',
                'multiple' => true,
                'expanded' => false,
                'required' => true,
                'error_bubbling' => true,
                'choices' => $options['plants'],
                'choice_label' => function ($value) {
                    if (is_object($value)) {
                        return $value->__toString();
                    } else {
                        return $value;
                    }
                },
                'choice_value' => function ($value) {
                    if (is_object($value)) {
                        return $value->getId();
                    } else {
                        return 0;
                    }
                },
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez choisir au moins une plante',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advice::class,
        ]);
        $resolver
            ->setRequired('plants')
            ->setAllowedTypes('plants', 'array');
    }
}
