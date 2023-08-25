<?php namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Category
 *
 * @package App\Models\Blog
 *
 * @author  jedy
 */

class CategoryBack extends Model
{
	use HasFactory;

    public $table = 'categroy_view_statistics';

    protected $fillable = ['id','view_incr','category_id','ip_address'];


    public function category(): BelongsTo {
        return $this->belongsTo(Category::class,'category_id');
    }
}
