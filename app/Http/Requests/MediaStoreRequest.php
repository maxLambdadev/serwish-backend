<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 * )
 */
class MediaStoreRequest extends FormRequest
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
     * @OA\Property(type="string", format="binary", property="file"),
     * @OA\Property(type="number", property="group_id"),
     * @OA\Property(type="boolean", property="is_main", default=false),
     */
    public function rules()
    {
        return [
            'file'=>'required|max:85120'
        ];
    }
}
