<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema()
 */
class OrderGroupMessage extends Model
{

    protected $table = 'order_group_messages';

    protected $fillable = [
        'id','message','type','seen','sender','order_groups_id','sender_id'
    ];

    public function group(){
        return $this->belongsTo(OrderGroups::class,'order_groups_id');
    }
}

