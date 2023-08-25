<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locales extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'locales';

    protected $fillable = [
        'name','original_name', 'iso_code','is_active','is_default'
    ];
}
