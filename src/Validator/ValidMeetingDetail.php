<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
class ValidMeetingDetails extends Constraint
{
    public $message = 'The meeting details are invalid.';

    public function validatedBy()
    {
        return static::class . 'Validator';
    }
}
