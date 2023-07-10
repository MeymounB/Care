<?php

namespace App\Form\Registration;

use App\Entity\Botanist;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class BotanistFormType extends UserFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('certif', FileType::class, [
                'mapped' => false,
                'label' => 'Document de certification',
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => ['application/pdf'],
                        'maxSize' => 1024 * 512 // max file size is
                    ])
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Botanist::class,
        ]);
    }
}
