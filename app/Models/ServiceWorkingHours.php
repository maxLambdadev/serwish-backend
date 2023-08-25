<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceWorkingHours extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'service_working_hours';

    protected $fillable = [
        'id','service_id','type', 'week_day','start_at','end_at','saturday_start_at',
        'saturday_end_at','sunday_start_at','sunday_end_at'
    ];

    public function service(){
        return $this->belongsTo(Services::class,'service_id');

    }
}
