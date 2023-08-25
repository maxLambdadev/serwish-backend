<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Beautifier;
use App\Helpers\DataMutator;
use App\Helpers\ServiceHelper;
use App\Helpers\StatisticHelper;
use App\Helpers\WebpConverter;
use App\Http\Requests\Api\AddCityRequest;
use App\Http\Requests\Api\AddTagRequest;
use App\Http\Requests\Api\CreateServiceRequest;
use App\Http\Requests\Api\NameSearchRequest;
use App\Http\Requests\Api\SearchRequest;
use App\Http\Requests\Api\ServiceCallReviewRequest;
use App\Http\Requests\Api\ServicesListRequest;
use App\Http\Requests\Api\UpdateServiceRequest;
use App\Http\Requests\CountButtonClickRequest;
use App\Http\Requests\MediaRemoveRequest;
use App\Http\Requests\MediaStoreRequest;
use App\Models\Blog\Category;
use App\Models\City;
use App\Models\CityTranslation;
use App\Models\PayablePacket;
use App\Models\Resource;
use App\Models\ResourceToAny;
use App\Models\ServiceReview;
use App\Models\Services;
use App\Models\ServicesTranslation;
use App\Models\ServiceWorkingHours;
use App\Models\SpecialistReview;
use App\Models\Tags;
use App\Models\TagsTranslation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Gumlet\ImageResize;


/**
 *  Class ServiceController
 *  @package App\Http\Controllers\Api
 *  @author jedy
 */
class ServiceController extends ApiController
{

    /**
     * @OA\Get(
     *     tags={"Services"},
     *     path="/api/services/list",
     *     summary="Get services ",
     *     operationId="getFilteredService",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/ServicesListRequest")
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
    public function list(ServicesListRequest $request)
    {

        $perPage = 10;
        $extraData = null;

        $list = Services::with(['images','specialist','cities','workingHours','categories','tags']);

        if ($request->ids){
            $list = Services::with(['images','specialist','cities','workingHours','categories','tags'])->whereIn('id',$request->ids);
        }

        try {

            $list = $list->where('review_status','=','published')->where('is_active','=',true);
            if ($request->input('locale')){
                $list = $list->whereHas('translations', function ($query) use ($request) {
                    $query->where('locale','=',$request->input('locale'));
                });
            }

            if ($request->categories){
                $cat = Category::with(['tags'])->find($request->categories);
                $extraData = $cat;

                $list = $list->whereHas('categories',function ($q) use ($request){
                    if (is_array($request->categories)){
                        $q->whereIn('services_to_categories.category_id',$request->categories);
                    }else{
                        $q->where('services_to_categories.category_id',$request->categories);
                    }
                });


                // unique user count fetching categories array and increment +1 every parent
                $catArray = is_array($request->categories) ? $request->categories : [$request->categories];
                foreach ( $catArray as $catId){

                    $cat = Category::where('id',$catId)->first();
                    if($cat !== null){
                        StatisticHelper::updateCategoryViewStatistic($cat->id, $request->ip());
                    }
                }
            }

            if ($request->specialists){
                $list = $list->whereHas('specialist',function ($q) use ($request){
                    if (is_array($request->specialists)){
                        $q->whereIn('user_id',$request->specialists);
                    }else{
                        $q->where('user_id',$request->specialists);
                    }
                });
            }

            if ($request->has_serwish_quality){
                $list = $list->where('has_serwish_quality','=', $request->has_serwish_quality);
            }
            if ($request->cities){
                $list = $list->whereHas('cities', function ($query) use ($request){
                    $query->whereIn('city_id',$request->cities);
                });
            }
            if ($request->personal){
                $list = $list->whereHas('specialist',function ($query) use ($request){
                   $query->where('personal', '=', $request->personal);
                });
            }


            if ($request->perPage){
                $perPage = $request->perPage;
            }


            $list =   $list->orderBy('has_serwish_quality','DESC')
                ->orderBy('packet_id','ASC')
                ->orderBy('sorted_at','DESC')
                ->orderBy('priority','DESC')
                ->paginate($perPage);

            $list = ServiceHelper::getOrderedServices($list);


            $list->map(function ($item) use ($request){
                $mainImage = $item->images()->wherePivot('is_active','=',true)->first();
                $item['mainPicture'] = $mainImage == null ? $item->images()->first() : $mainImage;

                $locale = $request->input('locale');
                if (!$request->input('locale')){
                    $locale = App::getLocale();
                }
                unset($item->translated);
                $item->translated = $item->translations->where('locale','=',$request->input('locale'))->first();
                $item->translated->slug = Beautifier::geoToEng($item->title );
                $item->translations = null;
                unset($item->translations);

                $item->translated->description = Str::of(strip_tags($item->description))->limit(200);

                $fullCount = $item->reviews()->count();
                $likeCount =  $item->reviews()->where('likes','=',true)->count();
                $percent = $likeCount > 0 ? $likeCount * 100 / $fullCount : 0;

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

            return $this->response(200, [
                    'list' => $list,
                    'category' => $extraData
                ]
            );

        }catch (InvalidArgumentException | \Illuminate\Database\QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     tags={"Services"},
     *     path="/api/services/{id}/{locale}",
     *     summary="Get single service ",
     *     operationId="getSingleService",
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
    public function single( $id, string $locale, Request $request)
    {

            $service = Services::with('images','specialist','cities','workingHours','categories','tags','reviews.user.images')
                ->where('id','=',$id)
                ->where('is_active','=',true)
                ->where('review_status','=','published')
                ->whereHas('translations', fn($q) => $q->where('locale','=',$locale) )
                ->firstOrFail();

            $service->reviews->map(function ($item){
                if ($item->user->extraPic != null){
                    $item->user->image = $item->user->extraPic;
                }else{
                    $img = !$item->user->images->isEmpty() ? $item->user->images[0] : null;
                    $item->user->image = $img != null ? asset('storage/'.$img->path) : null;
                }
            });

            if ($service == null){
                return $this->response(404, error: "service not found");
            }

            $service->viewCount = $service->viewCount + 1;
            $service->save();


        $service->service_name = $service->service_name;

        if ($service->contact_number == null){
            $service->contact_number = $service->specialist->phone_number;
        }

        unset($service->translated);
        $service->translated = $service->translations->where('locale','=',$locale)->first();

        $service->translated->slug = Beautifier::geoToEng($service->title );

        unset($service->translations);

        $fullCount = $service->reviews()->count();
        $likeCount = $service->reviews()->where('likes','=',true)->count();
        $percent = $likeCount > 0 ? $likeCount * 100 / $fullCount : 0;


        $service['likePercent'] = number_format($percent,0) ;
        $service['totalReviews'] = $fullCount;

        StatisticHelper::updateServiceStatistic($service->id, $request->ip());

        if ($service != null){
            return $this->response(200,$service);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Services"},
     *     path="/api/services/call/review",
     *     summary="call review ",
     *     operationId="saveReviewForService",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/ServiceCallReviewRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "success":true}, summary="some"),
     *         )
     *     ),
     * )
     */
    public function phoneCallback(ServiceCallReviewRequest $request){
        $service = Services::findOrFail($request->service_id);
        $callback = ServiceReview::create([
            'value' => $request->value,
            'service_id'=>$service->id
        ]);
        return $this->response(200, $callback);
    }
    /**
     * @OA\Get(
     *     tags={"Services"},
     *     path="/api/services/call/specialist/review",
     *     summary="call specialist review ",
     *     operationId="phoneSpecialistCallback",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/SpecialistReview")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "success":true}, summary="some"),
     *         )
     *     ),
     * )
     */
    public function phoneSpecialistCallback(Request $request){
        $service = User::findOrFail($request->user_id);
        $callback = SpecialistReview::create([
            'value' => $request->value,
            'user_id'=>$service->id
        ]);
        return $this->response(200, $callback);
    }

    /**
     * @OA\Post(
     *     tags={"Services"},
     *     path="/api/services/create-basic",
     *     summary="creating service",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/CreateServiceRequest"
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
    public function createService(CreateServiceRequest $request)
    {
        $user = Auth::user();
        if ($user->client_type != 'employee' ){
            return $this->response(400, error:'client must be employee to add services');
        }
        $data = $request->all();
        $service = DB::transaction(function () use ($user,$data,$request){
            $service = new Services($data);
            $service->user_id = $user->id;
            $service->sorted_at = Carbon::now();
            $service->save();

            //if sent single category just convert to array
            if ($request->categories && !is_array($request->categories)){
                $data['categories'] = [$request->categories];
            }
            $translatable = $request->only('title','description','locale');
            $translatable['services_id'] = $service->id;
            ServicesTranslation::create($translatable);
            $service->categories()->attach($data['categories']);
            return $service;
        });
        return $this->response(200, $service);
    }

    /**
     * @OA\Post(
     *     tags={"Services"},
     *     path="/api/services/update-basic",
     *     summary="update service",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/UpdateServiceRequest"
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
    public function updateBasic(UpdateServiceRequest $request)
    {
        $user = Auth::user();

        $data = $request->all();

        $service = DB::transaction(function () use ($user,$data,$request){

            $service = Services::findOrFail($data['id']);
            if ($user->hasRole('administrator') || $user->hasRole('moderator')){
                $service->review_status = 'published';
                $service->reviewer_id = $user->id;
            }

            $service->update($data);

            //if sent single category just convert to array
            if ($request->categories && !is_array($request->categories)){
                $data['categories'] = [$request->categories];
            }

            $translatableRecord = ServicesTranslation::where('services_id','=',$request->id);

            if ($request->input('locale')){
                $translatableRecord = $translatableRecord->where('locale','=',$request->input('locale'));
            }
            $translatableRecord = $translatableRecord->first();

            $translatable = $request->only('title','description','locale');
            $translatable['services_id'] = $service->id;
            if ($translatableRecord == null) $translatableRecord = new ServicesTranslation();
            $translatableRecord->fill($translatable)->save();

            if ($request->categories){
                $service->categories()->sync($data['categories']);
            }

            if ($request->workType && $request->workType != "full"){
                $reqData = $request->only('workingDays',"saturday","sunday");
                $workingHours = [
                    'type'          => $request->workType,
                    'service_id'    => $service->id,
                    'start_at'      =>  isset($reqData['workingDays']) ? $reqData['workingDays']['start_at'] : null,
                    'end_at'        =>  isset($reqData['workingDays']) ? $reqData['workingDays']['end_at'] : null,
                    'saturday_start_at'      => isset( $reqData['saturday']) ? $reqData['saturday']['start_at'] : null,
                    'saturday_end_at'      => isset( $reqData['saturday']) ? $reqData['saturday']['end_at'] : null,
                    'sunday_start_at'      => isset( $reqData['sunday']) ? $reqData['sunday']['start_at'] : null,
                    'sunday_end_at'      =>  isset( $reqData['sunday']) ? $reqData['sunday']['end_at'] : null
                ];
                $wH = $service->workingHours;
                if ($wH == null){
                    $wH = ServiceWorkingHours::create($workingHours);
                }else{
                    $wH = $wH->fill($workingHours);
                }
                $wH->save();
                return $service->workingHours;
            }
            return $service;
        });
        return $this->response(200, $service);
    }


    public function uploadLargeFiles(Request $request) {

        if (!$request->file('file')){
            return response('',201)->withHeaders([
                'Location' => 'https://api.serwish.ge/api/services/add-images?service-id='.time()
            ]);
        }

        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            return response()->json([
                'error'=>'not uploaded'
            ]);
        }

        $fileReceived = $receiver->receive(); // receive file

        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded

            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

            //file store here

            // delete chunked file
            unlink($file->getPathname());

            return response()->json([
//                'path' => asset('storage/' . $path),
                'path' => asset('storage/' . 'demo'),
                'filename' => $fileName
            ]);
        }


        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }

    /**
     * @OA\Post(
     *     tags={"Services"},
     *     path="/api/services/add-images",
     *     summary="attach images ",
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
    public function attachImages(MediaStoreRequest $request)
    {


        $user = Auth::user();
        Validator::make($request->all(),[
            'is_main'   => 'required',
            'service_id'   => 'required',
        ])->validate();

        $service = Services::findOrFail($request->service_id);

        if ($user->id != $service->user_id){
            return $this->response(400, error: "this service doesn't belongs to user");
        }


        $media = null;
        if ($request->file('file')){
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $filename = time().'_'.$file->getClientOriginalName();

            $filename = explode('.',$filename);
            $extension = $filename[count($filename) - 1];

            unset($filename[count($filename) - 1]);

            $file = $request->file('file')->storeAs('uploads', implode($filename).'.'.$extension);

            $created = WebpConverter::convertFromAny('app/public/'.$file);

            if ($created){
                $fileNameArray = explode('.', $file);
                $fileNameArray[count($fileNameArray) - 1] = 'webp';

                $file = implode('.', $fileNameArray);
            }

            $media = new Resource;
            $media->path = $file;
            $media->name = $file;
            $media->save();

            \App\Helpers\ImageResizer::cropper($file);

            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize('storage/'.$file);

            $service->images()->attach($media->id,['is_active'=>$request->is_main,'other_entity'=>Services::class]);
        }

        return $this->response(200, $media);
    }

    /**
     * @OA\Post(
     *     tags={"Services"},
     *     path="/api/services/remove-images",
     *     summary="remove images ",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/MediaRemoveRequest"
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
    public function removeImage(MediaRemoveRequest $request){
        $user = Auth::user();
        Validator::make($request->all(),[
            'service_id'   => 'required',
        ])->validate();
        $service = Services::findOrFail($request->service_id);
        if ($user->id != $service->user_id){
            return $this->response(400, error: "this service doesn't belongs to user");
        }

        DB::transaction(function () use ( $request ){
            $resource = Resource::findOrFail($request->media_id);
            ResourceToAny::where('resource_id','=',$resource->id)->delete();
            $resource->delete();
        });
        return $this->response(200, "");
    }

    public function removeService(){

    }


    /**
     * @OA\Post(
     *     tags={"Services"},
     *     path="/api/services/tags/sync",
     *     summary="sync tags to services",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/AddTagRequest"
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
    public function addTags(AddTagRequest $request){
        $user = Auth::user();
        $service = Services::findOrFail($request->service_id);
        if ($user->id != $service->user_id){
            return $this->response(400, error: "this service doesn't belongs to user");
        }

        $tags = [];
        foreach ($request->tags as $t){
            $checkTag = TagsTranslation::where('name','=',$t)
                ->where('locale','=',$request->input('locale'))
                ->first();
            if ($checkTag == null){
                $tag = Tags::create([
                    'is_active'      => true,
                    'user_id'        => $user->id,
                ]);
                $checkTag = TagsTranslation::create([
                    'name'      => $t,
                    'locale'    => $request->input('locale'),
                    'tags_id'=> $tag->id
                ]);
            }
            $tags[] = $checkTag->tags_id;
        }
        $service->tags()->sync($tags);
        return $this->response(200);
    }

    /**
     * @OA\Get(
     *     tags={"Services"},
     *     path="/api/services/tags/search",
     *     summary="Search tag by its name",
     *     operationId="getFilteredTags",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/NameSearchRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Tags")
     *         ))
     *      ),
     * )
     */
    public function findTag(NameSearchRequest $request){
        $tags = TagsTranslation::where('name', 'like', '%'.$request->name.'%')
            ->where('locale','=',$request->input('locale'))
            ->limit(100)
            ->get();

        return $this->response(200, $tags);
    }

    public function countButtonClick(CountButtonClickRequest $request){

        $service = Services::findOrFail($request->service_id);
        //todo old code cleanup also in admin panel
        //@depreacation  $service->buttonClicked = $service->buttonClicked +1;
        StatisticHelper::updateServiceClickStatistic($service->id, $request->ip());

        return $this->response(200);
    }

    /**
     * @OA\Post(
     *     tags={"Services"},
     *     path="/api/services/city/sync",
     *     summary="sync cities to services",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/AddCityRequest"
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
    public function addCity(AddCityRequest $request)
    {
        $user = Auth::user();
        $service = Services::findOrFail($request->service_id);
        if ($user->id != $service->user_id){
            return $this->response(400, error: "this service doesn't belongs to user");
        }

        $cities = City::whereIn('id',$request->cities)->pluck('id');

        $service->cities()->sync($cities);
        return $this->response(200);
    }

    /**
     * @OA\Get(
     *     tags={"Services"},
     *     path="/api/services/city/search",
     *     summary="Search city by its name",
     *     operationId="getFilteredCity",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/NameSearchRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/City")
     *         ))
     *      ),
     * )
     */
    public function findCity(NameSearchRequest $request){
        $tags = CityTranslation::where('name', 'like', '%'.$request->name.'%')
            ->where('locale','=',$request->input('locale'))
            ->limit(100)
            ->get();

        $tags = $tags->map(function ($item){
            $item->id = $item->city_id;
            return $item;
        });

        return $this->response(200, $tags);
    }

    /**
     * @OA\Get(
     *     tags={"Services"},
     *     path="/api/services/city/list",
     *     summary="Get cities ",
     *     operationId="getCities",
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/City")
     *         ))
     *      ),
     * )
     */
    public function cityList(ServicesListRequest $request)
    {
        $perPage = 100000;

        $list = City::orderBy('position','ASC')->orderBy('id','DESC')->paginate($perPage);

        try {
            $list->map(function ($item) use ($request){
                $locale = $request->input('locale');
                if (!$request->input('locale')){
                    $locale = App::getLocale();
                }
                unset($item->translated);
                $item->translated = $item->translations->where('locale','=',$request->input('locale'))->first();
                $item->translations = null;
                unset($item->translations);
                $item->translated->description = Str::of(strip_tags($item->description))->limit(200);
                return $item;
            });
            return $this->response(200, $list);
        }catch (InvalidArgumentException | \Illuminate\Database\QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     tags={"3rd"},
     *     path="/api/search",
     *     summary="search into platform",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/SearchRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "where":"array"}, summary="some"),
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
    public function search(SearchRequest $request)
    {
        $result = [];
        if (intval($request->key) > 0){

            $result['services'] = Services::with(['images','specialist','cities','workingHours','categories','tags'])
                ->where('is_active','=',true)
                ->where('review_status','=','published')
                ->where('id','=',intval($request->key))
                ->get() ;

            if (!$result['services']->isEmpty()){
                $result['services'] = DataMutator::mutateServices($result['services']);
            }

            return $this->response(200, $result);
        }

        if ( array_search('services',$request->where) !== false ){
            $result['services'] = Services::with(['images','specialist','cities','workingHours','categories','tags'])
                ->where('is_active','=',true)
                ->where('review_status','=','published');

            $result['services'] = $result['services']->whereHas('translations', function ($query) use ($request){
               $query->where('title','like', '%'.$request->key.'%');
            })->orWhereHas('categories.translations',function ($query) use ($request){
                $query->where('title','like', '%'.$request->key.'%');
            }) ->where('review_status','=','published');
//            $result['services'] = $result['services']->orWhereHas('tags.translations',function ($query) use ($request){
//                $query->where('name','like', '%'.$request->key.'%');
//            });

            $result['services'] = $result['services']->get();
            $result['services'] = DataMutator::mutateServices($result['services']);
        }

        if ( array_search('categories',$request->where) !== false ) {
            $result['categories'] = Category::with(['childrens'])->where('type','=', 'SPECIALIST');
            $result['categories'] = $result['categories']->whereHas('translations', function ($query) use ($request){
                $query->where('title','like', '%'.$request->key.'%');
            })
            ->where('isActive','=',true);
            $result['categories'] = $result['categories']->get();
            $result['categories'] = DataMutator::mutateCategories($result['categories']);
        }

        if ( array_search('specialist',$request->where) !== false ) {
            $result['specialist'] = User::with(['images', 'services.categories']);
            $result['specialist'] = $result['specialist']->where('name','like', '%'.$request->key.'%');

            $result['specialist'] = $result['specialist']->get();
            $result['specialist'] = DataMutator::mutateSpecialists($result['specialist']);
        }
        return $this->response(200, $result);
    }
}
