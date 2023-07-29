<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

// @TODO: https://symfony.com/doc/current/form/data_transformers.html
// I've tried but I can't get it to work. I'm not sure if it's because of the DateTimeType or the DateToStringTransformer.
// You can take a look at the error by decomenting the line 55 in src\Form\AppointmentType.php

class DateToStringTransformer implements DataTransformerInterface
{
    public function transform($date)
    {
        if (null === $date) {
            return '';
        }

        return $date->format('d-m-Y H:i');
    }

    public function reverseTransform($date)
    {
        var_dump($date);
        if (!$date) {
            return null;
        }

        $dateObj = \DateTime::createFromFormat('d-m-Y H:i', $date);

        if (!$dateObj) {
            throw new TransformationFailedException(sprintf('La date "%s" n\'est pas au bon format !', $date));
        }

        return $dateObj;
    }
}
