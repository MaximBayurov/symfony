<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RegistrationSpam extends Constraint
{
    public $message = 'Ботам здесь не место';
}
