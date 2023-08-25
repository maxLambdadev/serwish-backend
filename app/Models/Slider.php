<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Localized;

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
 *      description="sort_order",
 *      title="sort_order",
 *      property="sort_order"
 *  ),
 *  @OA\Property(
 *      format="boolean",
 *      description="isActive",
 *      title="isActive",
 *      property="isActive"
 *  ),
 *  @OA\Property(
 *      property="translated",
 *      ref="#/components/schemas/SliderTranslation"
 * )
 * )
 */
class Slider extends Localized
{
	use HasFactory;

    protected $fillable = ['sort_order','isActive', 'showMoreBtn'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(){
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',Slider::class);
    }
}
