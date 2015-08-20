<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accounts extends Model {

    protected $table = 'accounts';

    public $timestamps = false;

    protected $fillable = array('name', 'username', 'email', 'about', 'acctype');

}