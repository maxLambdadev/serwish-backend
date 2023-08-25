<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @author jedy
 */

/**
 * @OA\Schema(
 * )
 */
class AddReviewRequest extends FormRequest
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
     * @return array
     *
     * @OA\Property(type="string", property="description",description="description")
     * @OA\Property(type="string", property="likes", description="likes")
     * @OA\Property(type="int", property="service_id",description="service_id")
     */
    public function rules()
    {
        return [
            'service_id'=> 'required',
            'description'=> 'required',
            'likes'=> 'required',
        ];
    }
}
