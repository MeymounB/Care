<?php

namespace App\Form\Registration;

use App\Entity\Botanist;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BotanistFormType extends UserFormType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Botanist::class,
        ]);
    }
}
