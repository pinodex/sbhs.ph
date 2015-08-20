<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordMatchValidator extends ConstraintValidator {

	public function validate($value, Constraint $constraint) {
		if ($value && !password_verify($value, $constraint->to)) {
			$this->context->addViolation($constraint->message);
		}
	}

}