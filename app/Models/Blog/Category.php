<?php namespace App\Models\Blog;

use App\Models\CallRequests;
use App\Models\Resource;
use App\Models\Services;
use App\Models\Tags;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use J3dyy\LaravelLocalized\DB\Localized;

/**
 * Class Category
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
 *      format="boolean",
 *      description="isActive",
 *      title="isActive",
 *      property="isActive"
 *  ),
 *  @OA\Property(
 *      format="int64",
 *      description="author_id",
 *      title="author_id",
 *      property="author_id"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="type",
 *      title="type",
 *      property="type"
 *  ),
 *  @OA\Property(
 *      property="translated",
 *      ref="#/components/schemas/CategoryTranslation"
 * )
 * )
 */
class Category extends Localized
{
	use HasFactory;

    protected $fillable = ['id','isActive','author_id','type','category_id','viewCount','sort_order', "blog_position"];

    protected $hidden = ['category_id'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(){
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',Category::class);
    }

    public function author(){
        return $this->hasOne(User::class, 'id','author_id');
    }

    public function parent(): BelongsTo {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function childrens(): HasMany {
        return  $this->hasMany(Category::class,'category_id');
    }


    public function services() {
        return $this->belongsToMany(Services::class,'services_to_categories','category_id','services_id');
    }

    public function callRequests()
    {
        return $this->hasMany(CallRequests::class,'category_id');
    }

    public function statBack()
    {
        return $this->hasMany(CategoryBack::class,'category_id');
    }


    public function tags(){
        return $this->belongsToMany(Tags::class, 'tags_to_categories','category_id','tag_id');
    }
}
