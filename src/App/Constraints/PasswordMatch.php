<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;

class PasswordMatch extends Constraint {

	public $to;

	public $message = 'Password does not match';

}