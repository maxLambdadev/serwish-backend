<?php

namespace App\Http\Controllers\Api;

use App\Helpers\DataMutator;
use App\Http\Requests\Api\FilterableSupportRequest;
use App\Http\Requests\Api\MakeCallRequest;
use App\Http\Requests\Api\MakeContactRequest;
use App\Models\Ads;
use App\Models\Blog\Category;
use App\Models\CallRequests;
use App\Models\ContactRequests;
use App\Models\Faq;
use App\Models\Slider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CommonController extends ApiController
{
    /**
     * @OA\Post(
     *     tags={"Common"},
     *     path="/api/make/call",
     *     summary="make call request",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/MakeCallRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "success":"empty"}, summary="some"),
     *         )
     *     )
     * )
     */
    public function makeCallRequest(MakeCallRequest $request){
        $data = $request->all();

        if (Category::find($request->category_id) !== null){
            CallRequests::create($data);
            return $this->response(200);
        }
        return $this->response(401, error: "cannot find category");
    }

    /**
     * @OA\Post(
     *     tags={"Common"},
     *     path="/api/make/contact",
     *     summary="make contact request",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/Faq"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "success":"empty"}, summary="some"),
     *         )
     *     )
     * )
     */
    public function makeContactRequests(MakeContactRequest $request){
        $data = $request->all();
        ContactRequests::create($data);
        return $this->response(200);
    }


    /**
     * @OA\Get(
     *     tags={"Common"},
     *     path="/api/faq",
     *     summary="Get faq list",
     *     operationId="getFaqList",
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
    public function faq( Request $request ){
//        $list = Redis::get('faq');

        try {
//            if ($list == null){
            $list = Faq::with('images')->where('is_active','=',true);


            $list = $list->orderBy('id','DESC')
                ->get();
//                ->toArray();

            $list = DataMutator::mutateImage($list);


            if ($request->locale){
                $list->map(function ($item) use ($request){

                    $locale = $request->input('locale');
                    if (!$request->input('locale')){
                        $locale = App::getLocale();
                    }

                    unset($item->translated);
                    $item->translated = $item->translations->where('locale','=',$request->input('locale'))->first();


                    $item->translations = null;
                    unset($item->translations);
                    return  $item;

                });

            }



            return $this->response(200, $list);
        }catch (InvalidArgumentException | QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }

    }


    /**
     * @OA\Get(
     *     tags={"Common"},
     *     path="/api/slider",
     *     summary="Get slider list",
     *     operationId="getSliderList",
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Slider")
     *         ))
     *      )
     * )
     */
    public function slider( ){
        $list = Slider::with('images');
        try {

            $list = $list->where('isActive','=',true)->orderBy('id','DESC')
                ->get();

            $list = DataMutator::mutateImage($list);
            return $this->response(200, $list);
        }catch (InvalidArgumentException | QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }

    }


    /**
     * @OA\Get(
     *     tags={"Common"},
     *     path="/api/ads",
     *     summary="Get ads list",
     *     operationId="getAdsList",
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Ads")
     *         ))
     *      )
     * )
     */
    public function ads( Request $request ){
        $list = Ads::with('images');
        try {
            if ($request->page){
                $list = $list->where('page','=', $request->page);
            }
            $list = $list->where('is_active','=',true)
                ->orderBy('order','DESC')
                ->get();
            $list = DataMutator::mutateImage($list);

            return $this->response(200, $list);
        }catch (InvalidArgumentException | QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }

    }

}
