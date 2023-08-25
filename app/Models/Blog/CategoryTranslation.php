<?php namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Translatable;


/**
 * Class CategoryTranslation
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
 *      format="int64",
 *      description="category_id",
 *      title="category_id",
 *      property="category_id"
 *  )
 * )
 */
class CategoryTranslation extends Translatable
{
	use HasFactory;

    protected $fillable = [
      'id','name','description','slug','meta','locale','category_id','title','meta_title','meta_description'
    ];

    protected $hidden = [
        'category_id', 'locale'
    ];
}
