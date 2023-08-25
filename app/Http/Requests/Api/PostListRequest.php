<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class PostListRequest extends FormRequest
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
     * @OA\Property(format="locale", default="ka",description="locale", property="locale"),
     * @OA\Property(format="integer", default=1,description="page", property="page"),
     * @OA\Property(format="integer", default=20,description="perPage", property="perPage"),
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
     * @OA\Property(type="enum", property="filterBy",enum={"monthly", "weekly", "newest"} )
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
