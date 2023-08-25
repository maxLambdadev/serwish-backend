<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderGroupPayment extends Model
{

    protected $table = 'order_group_payments';

    protected $fillable = [
        'id','status','amount','capture_method','order_id','shop_order_id',
        'show_shop_order_id_on_extract','redirect_url','intent','payment_hash','locale',
        'order_groups_id','message_id',
    ];

    protected $hidden = [
        'payment_hash'
    ];

    public function group(){
        return $this->belongsTo(OrderGroups::class,'order_groups_id');
    }
}
