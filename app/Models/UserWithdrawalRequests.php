<?php

namespace App\Models;

use App\Models\Orders\OrderGroups;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @OA\Schema
 */
class UserWithdrawalRequests extends Model
{
    protected $table = 'user_withdrawal_requests';

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'amount',
        'status',
        'user_id',
        'fullname',
        'iban'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
