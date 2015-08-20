<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banners extends Model {

    protected $table = 'banners';

    public $timestamps = false;

    protected $fillable = array('title', 'description', 'image');

}