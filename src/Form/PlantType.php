<?php

namespace App\Form;

use App\Entity\Plant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class PlantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
	            "label" => "Nom",
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'rows' => 10,
	                "placeholder" => "ex : Ma jolie rose violette que j'ai adopté en Janvier :)",
                ],
            ])
            ->add('species', TextType::class, [
	            "label" => "Espèce",
			])
	        ->add('photos', FileType::class, [
				'mapped' => false,
				'multiple' => true,
		        'constraints' => [
			        new All([
						'constraints' => [
							new File([
								'maxSize' => '1024k',
								'mimeTypes' => [
									'image/jpeg',
									'image/png',
								],
								'mimeTypesMessage' => 'Please upload a valid image',
							])
						]
					])
		        ],
	        ]);
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plant::class,
        ]);
    }
}
