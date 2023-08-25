<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class CreatePaymentRequest extends FormRequest
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
     * @OA\Property(format="string", default="10.20",description="30.15", property="amount"),
     * @OA\Property(format="integer", default="10",description="", property="group_id")
     */
    public function rules()
    {
        return [
            'packet_id' => 'required',
            'service_id'=>'required',
        ];
    }
}
