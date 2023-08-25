<?php namespace App\Models\Blog;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Localized;


/**
 * Class Posts
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
 *      format="int64",
 *      description="author_id",
 *      title="author_id",
 *      property="author_id"
 *  ),
 *  @OA\Property(
 *      format="int64",
 *      description="viewCount",
 *      title="viewCount",
 *      property="viewCount"
 *  ),
 *  @OA\Property(
 *      format="boolean",
 *      description="isActive",
 *      title="isActive",
 *      property="isActive"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="publish_at",
 *      title="publish_at",
 *      property="publish_at"
 *  ),
 *  @OA\Property(
 *      property="translated",
 *      ref="#/components/schemas/PostTranslation"
 * )
 * )
 */
class Post extends Localized
{
	use HasFactory;

    protected $fillable = [
      'author_id','viewCount','isActive','publish_at'
    ];

    protected $hidden = [
        'translations'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(){
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',Post::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class,'post_to_category','post_id','category_id');
    }

    public function author(){
        return $this->hasOne(User::class, 'id','author_id');
    }
}
