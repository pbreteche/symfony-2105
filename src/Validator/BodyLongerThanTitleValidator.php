<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BodyLongerThanTitleValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\BodyLongerThanTitle */

        if (null === $value || '' === $value) {
            return;
        }

        if (strlen($value->getBody()) >= 2 * strlen($value->getTitle())) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
