<?php namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Translatable;

/**
 * Class PostTranslation
 *
 * @package App\Models\Blog
 *
 * @author  jedy
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
 *      description="description",
 *      title="description",
 *      property="description"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="slug",
 *      title="slug",
 *      property="slug"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="meta",
 *      title="meta",
 *      property="meta"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="locale",
 *      title="locale",
 *      property="locale"
 *  ),
 *  @OA\Property(
 *      format="int64",
 *      description="post_id",
 *      title="post_id",
 *      property="post_id"
 *  )
 * )
 */
class PostTranslation extends Translatable
{
	use HasFactory;

    protected $fillable = [
      'id','name','description','slug','meta','locale','post_id'
    ];

    protected $hidden = [
        'post_id'
    ];
}
