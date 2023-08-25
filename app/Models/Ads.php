<?php

namespace App\Models;

use App\Models\Blog\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


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
 *      format="int64",
 *      description="name",
 *      title="name",
 *      property="name"
 *  ),
 *  @OA\Property(
 *      format="int64",
 *      description="order",
 *      title="order",
 *      property="order"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="link",
 *      title="link",
 *      property="link"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="page",
 *      title="page",
 *      property="page"
 *  ),
 * )
 */
class Ads extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'ads';

    protected $fillable = [
        'name','position','page','order','link','is_active'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(){
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',Ads::class);
    }
}
