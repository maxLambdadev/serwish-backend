<?php

namespace App\Models;

use App\Models\PayablePacket;
use App\Models\Services;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ServicePacket extends Model
{

    protected $table = 'packet_to_service';

    protected $fillable = [
        'id','status','amount','capture_method','order_id','shop_order_id',
        'show_shop_order_id_on_extract','redirect_url','intent','payment_hash','locale',
        'service_id','user_id','payable_packet_id','used'
    ];

    protected $hidden = [
        'payment_hash','service_id','user_id','payable_packet_id'
    ];

    public function service(){
        return $this->belongsTo(Services::class,'service_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function packet(){
        return $this->hasOne(PayablePacket::class,'payable_packet_id');
    }
}
