<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model {

    protected $table = 'events';

    public $timestamps = false;

    protected $fillable = array('title', 'description', 'datetime');

}