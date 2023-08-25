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
class CreateServiceRequest extends FormRequest
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
     * @OA\Property(type="number", format="double", property="price", default="0.0"),
     * @OA\Property(type="string", property="contact_number"),
     * @OA\Property(type="boolean", property="has_online_payment"),
     * @OA\Property(type="boolean", property="is_active"),
     * @OA\Property(type="string", property="location"),
     * @OA\Property(type="string", property="title"),
     * @OA\Property(type="string", property="description"),
     * @OA\Property(type="string", property="locale"),
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *      oneOf={
     *      @OA\Property(property="categories", type="integer", default="22,33,etc"),
     *      }
     *     ),
     *     description="categories you can specify multiple ids",
     *     property="categories"
     * )
     */
    public function rules()
    {
        return [
            'title'                 => 'required',
            'description'           => 'required',
            'locale'                => 'required',
            'categories'            => 'required'
        ];
    }
}
