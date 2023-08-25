<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class ServicesListRequest extends FormRequest
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
     * @OA\Property(format="array", default="ids",description="ids", property="ids"),
     * @OA\Property(format="string", default="ka",description="locale", property="locale"),
     * @OA\Property(format="string", default="personal",description="personal or business", property="personal"),
     * @OA\Property(format="integer", default=1,description="page", property="page"),
     * @OA\Property(format="integer", default=20,description="perPage", property="perPage"),
     * @OA\Property(format="boolean", default=false,description="has_serwish_quality", property="has_serwish_quality"),
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *      oneOf={
     *      @OA\Property(property="cities", type="string", default="22,33,etc"),
     *      }
     *     ),
     *     description="cities you can specify multiple ids",
     *     property="cities"
     * ),
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *      oneOf={
     *      @OA\Property(property="categories", type="string", default="22,33,etc"),
     *      }
     *     ),
     *     description="categories you can specify multiple ids",
     *     property="categories"
     * ),
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *      oneOf={
     *      @OA\Property(property="specialists", type="number", default="22,33,etc"),
     *      }
     *     ),
     *     description="specialist ids",
     *     property="specialists"
     * ),
     * @OA\Property(type="enum", property="filterBy",enum={"all", "with_serwish_quality", "without_serwish_quality"} )
     */
    public function rules()
    {
        return [
            'locale'=>'required',
        ];
    }
}
