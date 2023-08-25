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
class FaqTranslation extends Translatable
{
	use HasFactory;

    protected $fillable = [
      'title','description','button_link','locale','faq_id'
    ];

    protected $hidden = [
      'faq_id'
    ];
}
