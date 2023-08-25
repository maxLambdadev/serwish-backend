<?php

namespace App\Models;

use App\Models\Blog\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * values:
 * 1= არ დამირეკავს
 * 2= ვერ დავუკავშირდი
 * 3= არ მიპასუხა
 * 4= დავუკავშირდი ვერ შევთანხმდით
 * 5= დავუკავშირდი შევთანხმდით
 */


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
 *      description="value",
 *      title="value",
 *      property="value"
 *  ),
 * )
 */
class SpecialistReview extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'specialist_review';

    protected $fillable = [
        'value','user_id','extra'
    ];

    protected $hidden = [
        'user_id','extra'
    ];


}
