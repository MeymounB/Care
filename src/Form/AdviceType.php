<?php

namespace App\Form;

use AllowDynamicProperties;
use App\Entity\Advice;
use App\Entity\Plant;
use App\Entity\User;
use App\Repository\PlantRepository;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[AllowDynamicProperties] class AdviceType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security, PlantRepository $plantRepository, RequestRepository $requestRepository)
    {
        $this->security = $security;
        $this->plantRepository = $plantRepository;
        $this->requestRepository = $requestRepository;
    }

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
            ])
            ->add('plants', EntityType::class, [
                'class' => Plant::class,
                'choice_label' => 'name',
                'label' => 'Plantes',
                'multiple' => true,
                'expanded' => false,
                'required' => true,
                'attr' => [
                    'size' => 3,
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p IN (:plants)')
                        ->setParameter('plants', $this->getPlantChoices());
                },
            ]);
    }

    private function getPlantChoices(): array
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException('Access denied');
        }

        return $this->plantRepository->findBy(['particular' => $user->getId()], ['createdAt' => 'DESC']);
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
