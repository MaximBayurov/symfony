<?php

namespace App\Validator;

use App\Homework\RegistrationSpamFilter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RegistrationSpamValidator extends ConstraintValidator
{
    private RegistrationSpamFilter $spamFilter;
    
    public function __construct(RegistrationSpamFilter $spamFilter)
    {
        $this->spamFilter = $spamFilter;
    }
    
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\RegistrationSpam */

        if (null === $value || '' === $value) {
            return;
        }
    
        if ($this->spamFilter->filter($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
