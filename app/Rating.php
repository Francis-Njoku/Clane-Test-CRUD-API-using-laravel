<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    // Table name
    protected $table = 'rate';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

}
