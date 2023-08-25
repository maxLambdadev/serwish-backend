<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Translatable;

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
 *      description="locale",
 *      title="locale",
 *      property="locale"
 *  )
 *)
 */
class SliderTranslation extends Translatable
{
	use HasFactory;

    protected $fillable = [
        'title','description','locale','slider_id'
    ];

    protected $hidden = [
        'slider_id'
    ];

}

