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
class AddCityRequest extends FormRequest
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
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *      oneOf={
     *      @OA\Property(property="cities", type="string", default="city ids"),
     *      }
     *     ),
     *     description="city ids",
     *     property="cities"
     * )
     * @OA\Property(type="locale", property="locale",description="locale")
     */
    public function rules()
    {
        return [
            'cities'=> 'required',
            'service_id'=> 'required',
        ];
    }
}
