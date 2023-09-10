<?php

namespace App\Form\Registration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

abstract class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'En cochant cette case, j’accepte les conditions générales d’utilisation',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'error_bubbling' => true,
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'error_bubbling' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Votre prénom ne doit contenir que des lettres',
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'error_bubbling' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Votre nom ne doit contenir que des lettres',
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Adresse email',
                'required' => true,
                'error_bubbling' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'The password fields must match.',
                'first_options' => ['label' => 'Mot de passe', 'attr' => ['placeholder' => '']],
                'second_options' => ['label' => 'Confirmez votre mot de passe', 'attr' => ['placeholder' => '']],
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
                'error_bubbling' => true,
            ])
            ->add('cellphone', TextType::class, [
                'required' => true,
                'label' => 'Numéro de téléphone',
                'error_bubbling' => true,
            ]);
    }
}
