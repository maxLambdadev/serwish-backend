<?php namespace App\Models;

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

class ServiceStatistics extends Model
{
	use HasFactory;

    public $table = 'services_statistics';

    protected $fillable = ['id','count','service_id','ip_address'];

    public function service(): BelongsTo {
        return $this->belongsTo(Services::class,'service_id');
    }
}
