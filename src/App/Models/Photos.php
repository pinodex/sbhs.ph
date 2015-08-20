<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photos extends Model {

    protected $table = 'photos';

    public $timestamps = false;

    protected $fillable = array('file', 'thumb', 'gallery');
    
}