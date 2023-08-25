<?php

namespace App\Models;

use App\Models\Orders\OrderGroups;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use J3dyy\SmsOfficeApi\Client\Model\Balance;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @OA\Schema(
 *  @OA\Property(
 *      format="int64",
 *      description="id",
 *      title="id",
 *      property="id"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="name",
 *      title="name",
 *      property="name"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="email",
 *      title="email",
 *      property="email"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="gender",
 *      title="gender",
 *      property="gender"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="date_of_birth",
 *      title="date_of_birth",
 *      property="date_of_birth"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="phone_number",
 *      title="phone_number",
 *      property="phone_number"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="id_number",
 *      title="id_number",
 *      property="id_number"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="personal",
 *      title="personal",
 *      property="personal"
 *  ),
 *  @OA\Property(
 *      format="string",
 *      description="client_type",
 *      title="client_type",
 *      property="client_type"
 *  )
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'date_of_birth',
        'phone_number',
        'id_number',
        'personal',
        'client_type',
        'status',
        'is_active',
        'fb_id',
        'google_id',
        'extraPic'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'id_number'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $appends = ['slug'];

    public function getSlugAttribute(){
        $explode = explode(' ',$this->name);

        $name = count($explode) > 0 ? $explode[0] : $this->name;

        return $this->id.'-'.$name;
//        return str_replace(' ','-', $this->name);
    }

    public function services(){
        return $this->hasMany(Services::class,'user_id');
    }

    public function serviceStat(){
        return $this->hasManyThrough(ServiceStatistics::class, Services::class,'user_id','service_id');
    }

    public function balance(){
        return $this->hasOne(UserBalance::class,'user_id');
    }

    public function withdrawals(){
        return $this->hasMany(UserWithdrawalRequests::class, 'user_id');
    }

    public function approvedWithdrawals(){
        return $this->withdrawals()->where('status','approved');
    }


    public function needApproveWithdrawals(){
        return $this->withdrawals()->where('status','in_progress');
    }

    public function declinedWithdrawals(){
        return $this->withdrawals()->where('status','declined');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(){
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',User::class);
    }

    public function image()
    {
        return $this
            ->belongsToMany(Resource::class,'resource_to_any','other_id','resource_id')
            ->withPivot('is_active')
            ->wherePivot('other_entity','=',User::class)->first();
    }
}
