<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;



/**
 * @OA\Schema(
 *     required={"username","password","grant_type","client_id"}
 * )
 */
class ApiAuthRequest extends FormRequest
{


    /**
     * @return array
     *
     * @OA\Property(type="string", property="username"),
     * @OA\Property(type="string", property="password"),
     * @OA\Property(type="string", property="grant_type", default="password"),
     * @OA\Property(type="string", property="client_id", default="956b1f77-89aa-4902-ad4f-0d67bbffc4bf")
     */


}
