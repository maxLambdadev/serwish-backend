<?php

namespace App\Models\Orders;

use App\Models\Resource;
use App\Models\Services;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OrderGroups extends Model
{

    protected $table = 'service_order_groups';

    protected $fillable = [
        'id','name','room_state','customer_id','specialist_id','service_id'
    ];

    public function customer(){
        return $this->belongsTo(User::class,'customer_id');
    }

    public function specialist(){
        return $this->belongsTo(User::class,'specialist_id');
    }

    public function service(){
        return $this->belongsTo(Services::class, 'service_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(){
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',OrderGroups::class);
    }
}
