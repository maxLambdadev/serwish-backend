<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class ServiceCallReviewRequest extends FormRequest
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
     * @OA\Property(format="integer", default=1,description="value", property="value"),
     * @OA\Property(format="integer", default=1,description="service_id", property="service_id"),
     */
    public function rules()
    {
        return [
            'service_id'=>'required',
            'value' => 'required'
        ];
    }
}
