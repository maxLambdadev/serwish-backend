<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class SpecialistListRequest extends FormRequest
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
     * @OA\Property(format="integer", default=1,description="page", property="page"),
     * @OA\Property(format="integer", default=20,description="perPage", property="perPage"),
     * @OA\Property(format="boolean", default=false,description="has_serwish_quality", property="has_serwish_quality"),
     * @OA\Property(format="string", default="personal",description="personal or business", property="personal"),
     * @OA\Property(type="enum", property="filterBy",enum={"all", "with_serwish_quality", "without_serwish_quality"} )
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
