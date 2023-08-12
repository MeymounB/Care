<?php

namespace App\Form;

use App\Entity\Advice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdviceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 10,
                ],
            ])
            ->add('isPublic', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'label' => 'Voulez-vous rendre votre demande publique ?',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advice::class,
        ]);
    }

    // ->add('plants', EntityType::class, [
    //     'class' => Plant::class,
    //     'choice_label' => 'name',
    //     'label' => 'Plantes',
    //     'multiple' => true,
    //     'expanded' => true,
    // ])
    // ->add('botanist', EntityType::class, [
    //     'class' => Botanist::class,
    //     'choice_label' => 'name',
    //     'label' => 'Botaniste',
    // ]);
}
