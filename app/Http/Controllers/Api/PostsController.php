<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilterableSupportRequest;
use App\Http\Requests\Api\PostListRequest;
use App\Models\Blog\Post;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 *  Class ServiceController
 *  @package App\Http\Controllers\Api
 *  @author jedy
 */
class PostsController extends ApiController
{

    /**
     * @OA\Get(
     *     tags={"Post"},
     *     path="/api/posts/list",
     *     summary="Get posts ",
     *     operationId="getFilteredPosts",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/PostListRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
     *         ))
     *      )
     * )
     */
    public function list(PostListRequest $request)
    {
        $perPage = 10;

        $list = Post::with(['images']);

        try{
            if ($request->input('locale')){
                $list = $list->whereHas('translations', function ($query) use ($request) {
                    $query->where('locale','=',$request->input('locale'));
                });
            }
            if ($request->categories){
                $list = $list->whereHas('categories',function ($q) use ($request){
                    if (is_array($request->categories)){
                        $q->whereIn('category_id',$request->categories);
                    }else{
                        $q->where('category_id',$request->categories);
                    }
                });
            }

            if ($request->perPage){
                $perPage = $request->perPage;
            }

            if ($request->filterBy){
                //todo
                switch ($request->filterBy){
                    case 'monthly':
                        $list = $list->orderBy('id','DESC')->paginate($perPage);
                        break;
                    case 'weekly':
                        $list = $list->orderBy('id','DESC')->paginate($perPage);
                        break;
                    case 'newest':
                        $list = $list->orderBy('created_at','DESC')->paginate($perPage);
                        break;
                }
            }else{
                $list = $list->orderBy('id','DESC')->paginate($perPage);
            }

            $list = $list->map(function ($item) use ($request){
                $locale = $request->input('locale');
                if (!$request->input('locale')){
                    $locale = App::getLocale();
                }
                unset($item->translated);
                if ( $item->translations->isEmpty()) {
                    return abort(400,"no categoreies");
                }
                $item->translated = $item->translations->where('locale','=',$request->input('locale'))->first();
                unset($item->translations);
                $item->translated == null ?: $item->translated->description = Str::of(strip_tags($item->description))->limit(200);
                return $item;
            });

            return $this->response(200, $list);

        }catch (InvalidArgumentException | QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     tags={"Post"},
     *     path="/api/posts/{id}/{locale}",
     *     summary="Get posts ",
     *     operationId="getSinglePost",
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
     *         ))
     *      )
     * )
     */
    public function single(int $id, string $locale){
        $post =  Post::with('images')->where('id','=',$id)->whereHas('translations', fn($q) => $q->where('locale','=',$locale) )->first();
        if ($post == null){
            return $this->response(404, error: "service not found");
        }
        unset($post->translated);
        $post->translated = $post->translations->where('locale','=',$locale)->first();
        unset($post->translations);

        if ($post != null){
            return $this->response(200,$post);
        }
        return $this->response(404,error: "post not found");
    }
}
