<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;

class RecordExistence extends Constraint {

	public $validate;

	public $model;

	public $row;

	public $comparator = '=';

	public $exclude;

	public $message = 'Record already exists';

}