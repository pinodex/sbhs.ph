<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model {

    protected $table = 'gallery';

    public $timestamps = false;

    protected $fillable = array('name', 'description');

}