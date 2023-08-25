<?php namespace App\Models;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Localized;


/**
 * Class PayablePacket
 *
 * @package App\Models\Blog
 *
 * @author  jedy
 */

class PayablePacket extends Localized
{
	use HasFactory;

    protected $table = 'payablepacket';

    protected $fillable = [
      'price','valid_days','is_active','priority'
    ];

    protected $hidden = [
        'translations','is_active','priority','created_at','updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(){
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',PayablePacket::class);
    }


}
