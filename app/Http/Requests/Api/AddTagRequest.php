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
class AddTagRequest extends FormRequest
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
     *      @OA\Property(property="tags", type="string", default="some name"),
     *      }
     *     ),
     *     description="tag names",
     *     property="tags"
     * )
     * @OA\Property(type="locale", property="locale",description="locale")
     */
    public function rules()
    {
        return [
            'tags'      => 'required',
            'service_id'=> 'required',
            'locale'    => 'required',
        ];
    }
}
