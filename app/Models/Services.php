<?php namespace App\Models;

use App\Models\Blog\Category;
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
 *      format="double",
 *      description="price",
 *      title="price",
 *      property="price"
 *  ),
 *  @OA\Property(
 *      format="boolean",
 *      description="is_active",
 *      title="is_active",
 *      property="is_active"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="contact_number",
 *      title="contact_number",
 *      property="contact_number"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="price_type",
 *      title="price_type",
 *      property="price_type"
 *  ),
 *  @OA\Property(
 *      format="boolean",
 *      description="has_online_payment",
 *      title="has_online_payment",
 *      property="has_online_payment"
 *  ),
 *  @OA\Property(
 *      format="boolean",
 *      description="has_serwish_quality",
 *      title="has_serwish_quality",
 *      property="has_serwish_quality"
 *  ),
 *  @OA\Property(
 *      property="translated",
 *      ref="#/components/schemas/ServicesTranslation"
 * )
 * )
 */
class Services extends Localized
{
	use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'price',
        'price_type',
        'contact_number',
        'has_online_payment',
        'has_serwish_quality',
        'is_active',
        'publish_at',
        'viewCount',
        'reviewer_id',
        'review_status',
        'priority',
        'buttonClicked',
        'packet_id',
        'sorted_at',
        'packet_priority',
        'packet_date',
        'order_id',
        'review_date',
        'service_name'
    ];

    protected $hidden = [
      'publish_at','user_id',
//        'packet_id'
    ];

    protected $casts = [
        'has_serwish_quality' => 'boolean'
    ];

    public function specialist(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function reviewer(){
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(){
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',Services::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class,'services_to_categories','services_id','category_id');
    }


    public function tags(){
        return $this->belongsToMany(Tags::class, 'tags_to_service','service_id','tag_id');
    }

    public function cities(){
        return $this->belongsToMany(City::class, 'city_to_service','service_id','city_id');
    }

    public function workingHours(){
        return $this->hasOne(ServiceWorkingHours::class, 'service_id','id');
    }

    public function reviews(){
        return $this->hasMany(SpecialistCommentReview::class,'service_id');
    }

    public function vipPacket(){
        return $this->hasOne(PayablePacket::class,'id','packet_id');
    }

    public function lastVipTransaction(){
        return $this->hasMany(ServicePacket::class,'service_id','id')
            ->orderBy('created_at','DESC');

    }

    public function statistics(){
        $startDate = \Carbon\Carbon::now()
        ->subDays(5)
        ->startOfDay()
        ->toDateTimeString();

        return $this->hasMany(ServiceStatistics::class, 'service_id')
        ->where('created_at', '>=', $startDate);
    }
}
