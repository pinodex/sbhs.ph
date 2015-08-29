<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model {

    protected $table = 'cache';

    public $timestamps = false;

    protected $fillable = array('id', 'value', 'created');
    
}