<?php

namespace App\Form\Registration;

use App\Entity\Particular;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticularFormType extends UserFormType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Particular::class,
        ]);
    }
}
