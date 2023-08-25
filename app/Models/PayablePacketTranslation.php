<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Translatable;

/**
 * Class PostTranslation
 *
 * @package App\Models\Blog
 *
 * @author  jedy
 */

class PayablePacketTranslation extends Translatable
{
	use HasFactory;

    protected $table = 'payable_packets_translations';

    protected $fillable = [
      'id','name','description'
    ];

    protected $hidden = [
        'payblepacket_id','created_at','updated_at','payable_packet_id'
    ];
}
