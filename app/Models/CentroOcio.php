<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentroOcio extends Model
{
    protected $table = 'centros_ocio';
    
    protected $fillable = [
        'name',
        'location',
        'address',
        'email',
        'phone_number'
    ];
}