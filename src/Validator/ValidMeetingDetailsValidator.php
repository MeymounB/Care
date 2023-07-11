<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidMeetingDetailsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\ValidMeetingDetails */
        $appointment = $this->context->getObject();

        if ($value && null === $appointment->getAddress()) {
            // Si le rendez-vous est en personne et qu'aucune adresse n'est fournie, c'est une violation.
            $this->context->buildViolation($constraint->message)
                ->atPath('address')
                ->addViolation();
        } elseif (!$value && null === $appointment->getLink()) {
            // Si le rendez-vous est en ligne et qu'aucun lien n'est fourni, c'est une violation.
            $this->context->buildViolation($constraint->message)
                ->atPath('link')
                ->addViolation();
        }
    }
}
