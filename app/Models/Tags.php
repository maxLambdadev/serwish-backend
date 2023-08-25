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
 *      description="user_id",
 *      title="user_id",
 *      property="user_id"
 *  ),
 *  @OA\Property(
 *      format="boolean",
 *      description="is_active",
 *      title="is_active",
 *      property="is_active"
 *  ),
 *  @OA\Property(
 *      property="translated",
 *      ref="#/components/schemas/TagsTranslation"
 * )
 * )
 */
class Tags extends Localized
{
	use HasFactory;


    protected $fillable = [
      'id','is_active','user_id'
    ];

    protected $hidden = [
      'user_id'
    ];

    public function services(){
        return $this->belongsToMany(Services::class, 'tags_to_service','tag_id','service_id');
    }
}
