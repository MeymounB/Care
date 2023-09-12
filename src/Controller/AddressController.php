<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Particular;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/address')]
class AddressController extends AbstractController
{
    #[Route('/new-form', name: 'app_address_edit', methods: ['GET'])]
    public function newForm(): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        return $this->render('address/_form.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_address_new', methods: ['POST'])]
    public function new(Request $request, AddressRepository $addressRepository): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        $user = $this->getUser();

        if (!$user instanceof Particular) {
            throw $this->createAccessDeniedException('Access denied');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setParticular($user);
            $addressRepository->save($address, true);

            return new JsonResponse([
                'message' => 'The address has been created.',
                'address' => $address,
            ], 201);
        }

        return new JsonResponse([
            'message' => 'The address has not been created.',
            'form' => $this->renderView('address/_form.html.twig', [
                'address' => $address,
                'form' => $form->createView(),
            ]),
        ], 400);
    }
}
