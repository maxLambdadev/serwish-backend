<?php

namespace App\Http\Controllers\Api;

use App\Helpers\DataMutator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilterableSupportRequest;
use App\Models\Blog\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;

/**
 *  Class ServiceController
 *  @package App\Http\Controllers\Api
 *  @author jedy
 */
class CategoryController extends ApiController
{

    /**
     * @OA\Get(
     *     tags={"Category"},
     *     path="/api/categories/list",
     *     summary="Get categories by type",
     *     operationId="getFilteredCategories",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/FilterableSupportRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Category")
     *         ))
     *      )
     * )
     */
    public function list(FilterableSupportRequest $request){

        $entity = Category::with(['tags','images','childrens'=> function($query){
            $query->where('isActive','=',true);
        }]);

        try {
            $entity = $entity->where('category_id','=',null)
                ->where('type','=','SPECIALIST')
                ->where('isActive','=',true)
//                ->orderBy('id','DESC')
                ->orderBy('sort_order','DESC')->get();

            $entity = DataMutator::mutateCategories($entity,$request->input('locale'));

            return $this->response(200, $entity);

        }catch (InvalidArgumentException | QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }

    }
}
