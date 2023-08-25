<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Translatable;

/**
 * @author jedy
 */

/**
 * @OA\Schema(
 *  @OA\Property(
 *      format="int64",
 *      description="id",
 *      title="id",
 *      property="id"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="name",
 *      title="name",
 *      property="name"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="locale",
 *      title="locale",
 *      property="locale"
 *  )
 *)
 */
class CityTranslation extends Translatable
{
	use HasFactory;

    protected $table = 'city_translations';

    protected $fillable = [
        'id','name','meta','locale','city_id'
    ];

    protected $hidden = [
        'city_id','meta'
    ];

    public $timestamps = false;
}
