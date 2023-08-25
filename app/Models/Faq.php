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
 *      format="boolean",
 *      description="is_active",
 *      title="is_active",
 *      property="is_active"
 *  ),
 *  @OA\Property(
 *      property="translated",
 *      ref="#/components/schemas/FaqTranslation"
 * )
 * )
 */
class Faq extends Localized
{
	use HasFactory;

    protected $fillable = [
      'id','is_active'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(){
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',Faq::class);
    }
}
