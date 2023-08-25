<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Localized;

class Menu extends Localized
{
	use HasFactory;

    public function parent(){
        return $this->belongsTo(Menu::class,'parent_id','id');
    }

    public function childrens()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }
}
