<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCodes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'sms_codes';

    protected $fillable = [
        'code','used','user_id'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

}
