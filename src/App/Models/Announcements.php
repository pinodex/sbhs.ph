<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcements extends Model {

    protected $table = 'announcements';

    public $timestamps = false;

    protected $fillable = array('content');

}