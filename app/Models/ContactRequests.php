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
 *      format="boolean",
 *      description="seen",
 *      title="seen",
 *      property="seen"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="phone",
 *      title="phone",
 *      property="phone"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="title",
 *      title="title",
 *      property="title"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="description",
 *      title="description",
 *      property="description"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="subject",
 *      title="subject",
 *      property="subject"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="email",
 *      title="email",
 *      property="email"
 *  )
 * )
 */
class ContactRequests extends Model
{
    use HasFactory;

//    public $timestamps = false;

    protected $table = 'contact_requests';

    protected $fillable = [
        'seen','phone','title','description','subject','email'
    ];


}
