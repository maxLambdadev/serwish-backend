<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Beautifier;
use App\Helpers\DataMutator;
use App\Http\Requests\Api\AddReviewRequest;
use App\Http\Requests\Api\GetSpecialistRequest;
use App\Http\Requests\Api\PostListRequest;
use App\Http\Requests\Api\ServicesListRequest;
use App\Http\Requests\Api\SpecialistListRequest;
use App\Http\Requests\Api\SpecialistServicesListRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Requests\MediaStoreRequest;
use App\Models\PayablePacket;
use App\Models\Resource;
use App\Models\Services;
use App\Models\SmsCodes;
use App\Models\SpecialistCommentReview;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Gumlet\ImageResize;


/**
 *  Class UserController
 *  @package App\Http\Controllers\Api
 *  @author jedy
 */
class UserController extends ApiController
{

    public function __construct()
    {
        $this->middleware('api');
    }

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/user/me",
     *     summary="Get User ",
     *     operationId="getUserInformation",
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/User"
     *         ))
     *      )
     * )
     */
    public function me(PostListRequest $request)
    {
        $aUser = Auth::user();


            $user = User::with('balance','images')->findOrFail($aUser->id);
            $user['idNumber'] = $user->id_number;

            if ($user->extraPic != null){
                $user->image = $user->extraPic;
            }else{
                $img = !$user->images->isEmpty() ? $user->images[0] : null;
                $user->image = $img != null ? asset('storage/'.$img->path) : null;
            }
        return $this->response(200, $user );
    }

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/user/update",
     *     summary="Update user",
     *     operationId="updateUserInformation",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/UpdateUserRequest")
     *      ),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/User"
     *         ))
     *      )
     * )
     */
    public function updateUser(UpdateUserRequest $request){
        $user = Auth::user();
        $data = $request->all();

        if ($request->password){
            Validator::make($request->all(),[
                'password'   => 'required|confirmed',
            ])->validate();
            $data['password'] = bcrypt($request->password);
        }

        if ($request->phone_number){
            Validator::make($request->all(),[
                'sms_validation'   => 'required',
            ])->validate();
            $code = SmsCodes::where('code','=', $request->sms_validation)->where('used','=',false)->first();
            if ($code != null){
                $user->phone_number = $request->phone_number;
                $code->user_id = $user->id;
                $code->used = true;
                $code->update();
            }else{
                return $this->response(401,error:"incorrect code");
            }
        }

        $user->fill($data);

        $user->save();

        // //update redis
        // Redis::del('current_user.'.$user->id);
        // Redis::set('current_user.'.$user->id, json_encode($user));

        return $this->response(200, );
    }

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/user/add-profile-pic",
     *     summary="attach profile picture ",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/MediaStoreRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "success":true}, summary="some"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "errors": "arrayOf(errorKey:errorValue)"},  summary="some"),
     *         )
     *     )
     * )
     */
    public function setProfilePic(MediaStoreRequest $request)
    {
        $user = Auth::user();
        if ($request->file('file')){
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $file = $request->file('file')->storeAs('uploads',$filename);

            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize('storage/'.$file);

            $media = new Resource;
            $media->path = $file;
            $media->name = $file;
            $media->save();

            $explode228x228 = explode('.', $file);
            $extension = $explode228x228[count($explode228x228) - 1];
            unset($explode228x228[count($explode228x228) - 1]);
            array_push($explode228x228,"-228x228");
            $image228x228 = new ImageResize('storage/'.$file);
            $image228x228->resizeToBestFit(228,228);
            $image228x228->save('storage/'.implode($explode228x228).'.'.$extension);

            $explode228x176 = explode('.', $file);
            unset($explode228x176[count($explode228x176) - 1]);
            array_push($explode228x176,"-228x176");
            $image228x176 = new ImageResize('storage/'.$file);
            $image228x176->resizeToBestFit(228,176);
            $image228x176->save('storage/'.implode($explode228x176).'.'.$extension);

            $explode366x228 = explode('.', $file);
            unset($explode366x228[count($explode366x228) - 1]);
            array_push($explode366x228,"-366x228");
            $image366x228 = new ImageResize('storage/'.$file);
            $image366x228->resizeToBestFit(366,228);
            $image366x228->save('storage/'.implode($explode366x228).'.'.$extension);

            $user->images()->detach();
            $user->images()->attach($media->id,['is_active'=>true,'other_entity'=>User::class]);

//            $user->extraPic = $user->images()->first() !== null ?  'storage/'.$user->images()->first()->name : '';
            $user->save();

            Redis::del('current_user.'.$user->id);
            Redis::set('current_user.'.$user->id, json_encode($user->toArray()));

            return $this->response(200,[
                'success'=>true,
                'image'=>$media,
            ]);
        }

        return  $this->response(400, error: "file must");
    }


    /**
     * @OA\Post(
     *     tags={"Specialists"},
     *     path="/api/specialists/add-review",
     *     summary="attach profile picture ",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/AddReviewRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "success":true}, summary="some"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "errors": "arrayOf(errorKey:errorValue)"},  summary="some"),
     *         )
     *     )
     * )
     */
    public function addReview(AddReviewRequest $request)
    {
        $reviewer = Auth::user();

        $data = $request->all();

        $service = Services::find($data['service_id']);

        if ($request->specialist_id == $reviewer->id){
            return $this->response(400,'you cannot write review to yourself');
        }
        if($service == null){
            return $this->response(400,"service not exists");
        }


        $checkReviewExists = SpecialistCommentReview::where('service_id',$service->id)
            ->where('user_id','=', $reviewer->id)->first();

        if ($checkReviewExists !== null){
            return $this->response(400,'you already write response to this specialist');
        }

        $data['user_id'] = $reviewer->id;
        $data['specialist_id'] = $service->user_id;

        SpecialistCommentReview::create($data);
        return $this->response(200);
    }

    /**
     * @OA\Get(
     *     tags={"User"},
     *     path="/api/user/services",
     *     summary="Get user based services ",
     *     operationId="getUserServices",
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Services")
     *         ))
     *      )
     * )
     */
    public function services(ServicesListRequest $request)
    {
        $user = Auth::user();
        $services = Services::with(['images','workingHours','cities','categories','tags'])
            ->where('user_id', '=', $user->id)
            ->paginate( 10 );


        if ($request->input('locale')){
          $services->map(function ($item) use ($request){
                $locale = $request->input('locale');
                if (!$request->input('locale')){
                    $locale = App::getLocale();
                }
                unset($item->translated);
                $item->translated = $item->translations->where('locale','=',$request->input('locale'))->first();
                $item->translations = null;
                $item->translated->slug = Beautifier::geoToEng($item->title );
                unset($item->translations);

                $fullCount = $item->reviews()->count();
                $likeCount =  $item->reviews()->where('likes','=',true)->count();
                $percent = $likeCount > 0 ? $likeCount * 100 / $fullCount : 0;

                $item['reviews'] = SpecialistCommentReview::where('service_id','=', $item->id)->orderBy('created_at','DESC')->get();
                $item['likePercent'] = number_format($percent,0) ;
                $item['totalReviews'] = $fullCount;

              $item['vip_status'] = 'none';
              $item['vip_icon'] = 'none';
              if ($item->packet_id !== null){
                  $packet = PayablePacket::find($item->packet_id);
                  if ($packet !== null){
                      $mainImage = $packet->images()->first();
                      $item['vip_status'] = $packet->name;
                      if($mainImage != null){
                          $item['vip_icon'] = asset('/storage/'.$mainImage->path);
                      }
                  }
              }

                return $item;
            });
        }
        return $this->response(200, $services);
    }




    /**
     * @OA\Get(
     *     tags={"Specialists"},
     *     path="/api/specialist/list",
     *     summary="Get specialists ",
     *     operationId="getFilteredService",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/SpecialistListRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         ))
     *      ),
     * )
     */
    public function getSpecialists(SpecialistListRequest $request){
        $perPage = 10;
        $list = User::with(['images', 'services.categories','services.cities']);

        /**
         * from new task
         * სპეციალისტების გვერდზე და მთავარ გვერდზე არ გამოჩნდნენ ადმინისტრატორები და მოდერატორები (გიო)
         */
        $list = $list->whereHas('roles', function($query){
            $query->whereNotIn('name',['administrator','moderator']);
        });

        try {
            if ($request->perPage){
                $perPage = $request->perPage;
            }

            if ($request->cities){
                $list = $list->whereHas('services', function ($query) use ($request){
                    $query->whereHas('cities', function ($q) use ($request){
                        $q->whereIn('city_id',$request->cities);
                    });
                });
            }
            if ($request->has_serwish_quality){
                $list = $list->whereHas('services', function ($query) use ($request){
                    $query->where('has_serwish_quality',$request->has_serwish_quality);
                });
            }


            if ($request->onlyImage){
                $list = $list->whereHas('images');
            }

            if ($request->categories){

                $list = $list->whereHas('services.categories', function ($q) use ($request){
                    if (is_array($request->categories)){
                        $q->whereIn('services_to_categories.category_id',$request->categories);
                    }else{
                        $q->where('services_to_categories.category_id',$request->categories);
                    }
                });
            }

            if ($request->personal){
                $list = $list->where('personal','=',$request->personal);
            }

            if ($request->filterBy){
                switch ($request->filterBy){
                    case 'monthly':
                        $list = $list->whereHas('services');
                        $list = $list->leftJoin('services','services.user_id','=','users.id')
                            ->orderBy('services.viewCount','DESC')
                            ->paginate($perPage);
                        break;
                    case 'weekly':
                        $list = $list->orderBy('id','DESC')->paginate($perPage);
                        break;
                    case 'newest':
                        $list = $list->orderBy('created_at','DESC')->paginate($perPage);
                        break;
                    case 'serwish_quality':
                        $list = $list->whereHas('services');
                        $list = $list->leftJoin('services','services.user_id','=','users.id')
                            ->orderBy('services.has_serwish_quality','DESC')
                            ->paginate($perPage);
                        break;
                    case 'has_online_payment':
                        $list = $list->whereHas('services');
                        $list = $list->leftJoin('services','services.user_id','=','users.id')
                            ->orderBy('services.has_online_payment','DESC')
                            ->paginate($perPage);
                        break;
                }
            }
            else{
                $list = $list->withCount('services','serviceStat')
//                    ->orderBy('services_count', 'desc')
                    ->orderBy('service_stat_count', 'desc')
                    ->paginate($perPage);
            }

            $list->map(function ($item){
                $item->serviceCategories = "";

                if ($item->services != null){
                    foreach ($item->services as $service){
                        if (!$service->categories->isEmpty()){
                            foreach ($service->categories as $category){
                                $item->serviceCategories .= $category->title. ", ";
                            }
                        }
                    }
                }
                $item->serwish_quality = $item->services->where('has_serwish_quality','=', true)->count();

                unset($item->services);
                $reviews = SpecialistCommentReview::with('user.images')->whereIn('service_id' ,$item->services->pluck('id'));

                $fullCount = $reviews->count();
                $likeCount = $reviews->where('likes','=',true)->count();
                $percent = $likeCount > 0 ? $likeCount * 100 / $fullCount : 0;

                $item['likePercent'] = number_format($percent,0) ;
                $item['totalReviews'] = $fullCount;

                return $item;
            });


            return $this->response(200, $list);
        }catch (InvalidArgumentException | \Illuminate\Database\QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     tags={"Specialists"},
     *     path="/api/specialist/{spec_id}",
     *     summary="Get specialist  by id ",
     *     operationId="getSpecialistById",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/GetSpecialistRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         ))
     *      ),
     * )
     */
    public function getSpecialist(GetSpecialistRequest $request)
    {
        $user = User::with(['images','services.categories'])->findOrFail($request->user_id);
        $tmpArr = [];
        foreach ($user->services as $service){
            foreach ($service->categories as $cat){
                if (!isset($tmpArr[$cat->id])){
                    $tmpArr[$cat->id] = $cat->title;
                }
            }
        }
        $user['serwish_quality'] = $user->services()->where('has_serwish_quality','=', true)->count();
        unset($user->services);
        $user['categories'] = implode(",", $tmpArr);

        if ($user->extraPic != null){
            $user->image = $user->extraPic;
        }else{
            $img = !$user->images->isEmpty() ? $user->images[0] : null;
            $user->image = $img != null ? asset('storage/'.$img->path) : null;
        }

        $services = Services::where('user_id','=',$user->id)->get()->pluck('id')->toArray();



        $reviews = SpecialistCommentReview::with('user.images')->whereIn('service_id' ,$services);
        $user['reviews'] = $reviews->orderBy('created_at','DESC')->get();

        $fullCount = $reviews->count();
        $likeCount = $reviews->where('likes','=',true)->count();
        $percent = $likeCount > 0 ? $likeCount * 100 / $fullCount : 0;

        $user['likePercent'] = number_format($percent,0) ;
        $user['totalReviews'] = $fullCount;

//        unset($user->images);
        return $this->response(200,$user);
    }

    /**
     * @OA\Get(
     *     tags={"Specialists"},
     *     path="/api/specialist/{spec_id}/services",
     *     summary="Get specialist services by id ",
     *     operationId="getServicesBySpecialist",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/SpecialistServicesListRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Services")
     *         ))
     *      ),
     * )
     */
    public function getServices($user_id ,SpecialistServicesListRequest $request)
    {
        $perPage = 10;

        $list = Services::with(['images','workingHours','cities','categories','tags','specialist'])->where('user_id','=',$user_id);
        $list = $list->where('review_status','=','published')->where('is_active','=',true);

        $list = $list->orderBy('has_serwish_quality','DESC');
        $list = $list->orderBy('priority','DESC');
        $list = $list->orderBy('created_at','DESC');

        try {
            if ($request->input('locale')){
                $list = $list->whereHas('translations', function ($query) use ($request) {
                    $query->where('locale','=',$request->input('locale'));
                });
            }
            if ($request->categories){
                $list = $list->whereHas('categories',function ($q) use ($request){
                    if (is_array($request->categories)){
                        $q->whereIn('services_to_categories.category_id',$request->categories);
                    }else{
                        $q->where('services_to_categories.category_id',$request->categories);
                    }
                });
            }

            if ($request->perPage){
                $perPage = $request->perPage;
            }
            $list = $list
                ->orderBy('has_serwish_quality','DESC')
                ->orderBy('priority','DESC')
                ->orderBy('created_at','DESC')
                ->paginate($perPage);


            $list->map(function ($item) use ($request){
//            $list = $list->map(function ($item) use ($request){

                $mainImage = $item->images()->wherePivot('is_active','=',true)->first();

                $item['mainPicture'] = $mainImage == null ? $item->images()->first() : $mainImage;

                $locale = $request->input('locale');
                if (!$request->input('locale')){
                    $locale = App::getLocale();
                }


                unset($item->translated);
                $item->translated = $item->translations->where('locale','=',$request->input('locale'))->first();
                $item->translations = null;
                unset($item->translations);
                $item->translated->description = Str::of(strip_tags($item->description))->limit(200);
                $item->translated->slug = Beautifier::geoToEng($item->title );

                $fullCount = $item->reviews()->count();
                $likeCount =  $item->reviews()->where('likes','=',true)->count();
                $percent = $likeCount > 0 ? $likeCount * 100 / $fullCount : 0;

                $item['likePercent'] = number_format($percent,0) ;
                $item['totalReviews'] = $fullCount;

                if ($item->contact_number == null){
                    $item->contact_number = $item->specialist->phone_number;
                }

                $item['vip_status'] = 'none';
                $item['vip_icon'] = 'none';
                if ($item->packet_id !== null){
                    $packet = PayablePacket::find($item->packet_id);
                    if ($packet !== null){
                        $mainImage = $packet->images()->first();
                        $item['vip_status'] = $packet->name;
                        if($mainImage != null){
                            $item['vip_icon'] = asset('/storage/'.$mainImage->path);
                        }
                    }
                }
                return $item;
            });

            return $this->response(200, $list);
        }catch (InvalidArgumentException | \Illuminate\Database\QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     tags={"Specialists"},
     *     path="/api/specialists/{id}/reviews",
     *     summary="Get user based services ",
     *     operationId="getUserServices",
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SpecialistCommentReview")
     *         ))
     *      )
     * )
     */
    public function specialistReviews($id)
    {
        $user = User::find($id);


        $reviews = SpecialistCommentReview::with('user', 'user.images')
            ->where('specialist_id' , '=', $user->id );
        $reviews = $reviews->paginate(50);

        $fullCount = $reviews->count();
        $likeCount = $reviews->where('likes','=',true)->count();
        $percent = $likeCount > 0 ? $likeCount * 100 / $fullCount : 0;


        $reviews->map(function ($review){
            if ($review->user->extraPic != null){
                $review->user->image = $review->user->extraPic;
            }else{
                $img = !$review->user->images->isEmpty() ? $review->user->images[0] : null;
                $review->user->image = $img != null ? asset('storage/'.$img->path) : null;
            }
        });

        $reviews['likePercent'] = number_format($percent,0) ;
        $reviews['totalReviews'] = $fullCount;


        return $this->response(200, $reviews);
    }
}
