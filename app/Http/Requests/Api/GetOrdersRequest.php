<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class GetOrdersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     * @OA\Property(format="string", default="all",description="started,half_payment_requested,half_payment_approved,payment_finished,order_closed", property="type"),
     * @OA\Property(format="integer", default="10",description="", property="perPage")
     */
    public function rules()
    {
        return [
        ];
    }
}
