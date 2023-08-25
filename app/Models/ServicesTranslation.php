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
 *      description="title",
 *      title="title",
 *      property="title"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="description",
 *      title="description",
 *      property="description"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="locale",
 *      title="locale",
 *      property="locale"
 *  )
 *)
 */
class ServicesTranslation extends Translatable
{
	use HasFactory;

    protected $fillable = [
        'id','title','description','extra_data','locale','services_id'
    ];

    protected $hidden = [
        'services_id'
    ];
}
