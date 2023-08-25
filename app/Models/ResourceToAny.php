<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceToAny extends Model
{

    protected $table = 'resource_to_any';
    public $timestamps = false;

    protected $fillable = ['resource_id','other_id','other_entity','meta','is_active'];

}
