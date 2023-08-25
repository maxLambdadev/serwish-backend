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
class UpdateServiceRequest extends FormRequest
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
     * @OA\Property(type="number", format="servuice_id", property="id", description="required"),
     * @OA\Property(type="number", format="double", property="price", default="0.0" , description="optional"),
     * @OA\Property(type="string", property="price_type",description="price_type"),
     * @OA\Property(type="string", property="contact_number",description="optional"),
     * @OA\Property(type="boolean", property="has_online_payment",description="optional"),
     * @OA\Property(type="boolean", property="is_active",description="optional"),
     * @OA\Property(type="string", property="location",description="optional"),
     * @OA\Property(type="string", property="title",description="optional"),
     * @OA\Property(type="string", property="description",description="optional"),
     * @OA\Property(type="string", property="locale",description="optional"),
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *      oneOf={
     *      @OA\Property(property="categories", type="integer", default="22,33,etc"),
     *      }
     *     ),
     *     description="categories you can specify multiple ids optional",
     *     property="categories"
     * )
     * @OA\Property(type="string", property="workType",description="optional"),
     * @OA\Property(type="string", property="saturday",description="saturday:{start_at,end_at:00:0}"),
     * @OA\Property(type="string", property="sunday",description="sunday:{start_at,end_at:00:0}"),

     */
    public function rules()
    {
        return [
            'id'                 => 'required',
        ];
    }
}
