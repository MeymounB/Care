<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuthenticationController extends AbstractController
{
    private \Doctrine\Persistence\ObjectManager $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityManager = $registry->getManager();
    }

    #[Route('/testinsert', name: 'wilfriedgenie')]
    public function wilfriedGenie(Request $request): Response
    {
        $user = new User();

        $user->setEmail('test');
        $user->setFirstName('first');
        $user->setCellphone('06060606');
        $user->setPassword('pass');
        $user->setLastName('last');
        $user->setEmail('email');
        $user->setRoles(['role']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if (!$user) {
            return $this->redirectToRoute('login');
        }

        return $this->render('guest_template/connectionPage.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/', name: 'connection_page')]
    public function connectionRender(Request $request): Response
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', TextType::class)
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe foit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('cellphone', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'S\'INSCRIRE'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('home_page');
        }

        return $this->render('guest_template/connectionPage.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
