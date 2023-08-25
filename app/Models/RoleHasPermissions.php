<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermissions extends Model
{

    protected $table = 'role_has_permissions';

    public $timestamps = false;
//    protected $primaryKey = null;
//    public $incrementing = false;

    protected $fillable = [
        'role_id','permission_id','can_add','can_edit','can_delete'
    ];
}
