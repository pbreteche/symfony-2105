<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BodyLongerThanTitle extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Le corps doit être plus long que le titre (classe de validation)';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
