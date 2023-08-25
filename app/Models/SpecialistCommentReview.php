<?php

namespace App\Models;

use App\Models\Blog\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;


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
 *      description="specialist_id",
 *      title="specialist_id",
 *      property="specialist_id"
 *  ),
 *  @OA\Property(
 *      format="int64",
 *      description="user_id",
 *      title="user_id",
 *      property="user_id"
 *  ),
 *  @OA\Property(
 *      format="boolean",
 *      description="likes",
 *      title="likes",
 *      property="likes"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="description",
 *      title="description",
 *      property="description"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="locale",
 *      title="locale",
 *      property="locale"
 *  ),

 * )
 */
//specialist_id refctored to service_id
class SpecialistCommentReview extends Model
{
    use HasFactory;

    protected $table = 'specialist_comment_review';

    protected $fillable = [
        'locale','description','likes','user_id','service_id','specialist_id'
    ];



    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

}
