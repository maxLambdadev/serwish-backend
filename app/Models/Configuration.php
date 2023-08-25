<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'configuration';

    protected $fillable = [
        'key','data'
    ];

    public  static function get_config(string $key){
        $entity = Configuration::where('key','=',$key)->first();
        if ($entity) {
            return json_decode($entity->data);
        } else return null;
    }
}
