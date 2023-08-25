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
 *      description="category_id",
 *      title="category_id",
 *      property="category_id"
 *  ),
 *  @OA\Property(
 *      format="boolean",
 *      description="is_called",
 *      title="is_called",
 *      property="is_called"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="phone_number",
 *      title="phone_number",
 *      property="phone_number"
 *  ),

 * )
 */
class CallRequests extends Model
{
    use HasFactory;

//    public $timestamps = false;

    protected $table = 'call_requests';

    protected $fillable = [
        'is_called','category_id','phone_number'
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

}
