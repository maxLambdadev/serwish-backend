<?php

namespace App\Http\Controllers\Manager\Services;

use App\Exports\ServiceExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SavePostRequest;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\City;
use App\Models\ServiceReview;
use App\Models\Services;
use App\Models\Tags;
use App\Models\TagsTranslation;
use App\Models\User;
use App\Models\ServiceStatistics;
use App\Models\ServicePhoneClickStatistics;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class ServiceController extends BaseController
{
    public function __construct() {
        $this->viewData = [
            'form' => 'manager.pages.service.form',
            'my-approvals' => 'manager.pages.service.my-approvals',
            'list' => 'manager.pages.service.list'
        ];

        parent::__construct();
    }

    public function list(Request $request, ?array $ordering = ['calls_count', 'DESC']) {
        // $date = str_replace('+', ' ', request()->get('daterange'));
        // $dates = explode('-', $date);
        // $startDate = (!empty(request()->get('daterange'))) ? Carbon::parse($dates[0]) : Carbon::now()->startOfDay()->toDateTimeString();
        // $endDate = (!empty(request()->get('daterange'))) ? Carbon::parse($dates[1])->addDay() : Carbon::now()->endOfDay()->toDateTimeString();
     
        $this->response['categories'] = Category::with(['tags', 'images', 'childrens' => function($query){
            $query->where('isActive', '=', true);
        }])
            ->where('type', '=', 'SPECIALIST')
            ->where('isActive', '=', true)
            ->where('category_id', '=', null)
            ->get();
        
        if ($request->review_type == 'started') {
            // $this->response['list'] = $this->model->orderBy('created_at','DESC')->get();
            $this->model = $this->model->where('review_status', '=', 'started');
            
            // return $this->responseView($this->viewData['list']);
            $this->model = $this->model
            ->leftJoin('services_statistics', 'services.id', '=', 'services_statistics.service_id')
            ->leftJoin('services_phone_click_statistics', 'services.id', '=', 'services_phone_click_statistics.service_id')
            ->selectRaw('services.*, COUNT(DISTINCT services_statistics.id) as views_count, COUNT(DISTINCT services_phone_click_statistics.id) as calls_count')
            ->groupBy('services.id')
            ->orderBy('created_at','DESC');


            $mydata = parent::list($request, $ordering);

            $cats = DB::table('services_to_categories')->get();

            foreach ($mydata['list'] as $serv) {
                $new_cats = [];

                foreach ($cats as $cat) {
                    if ($cat->services_id === $serv['id']) {
                        array_push($new_cats, $cat->category_id);
                    }
                }

                $serv['category'] = $new_cats;
            }

            $page = 1;

            if (!is_null($request->page) && isset($request->page)) {
                $page = $request->page;
            }
            $parent_cats = [];
            foreach($mydata["categories"] as $categories){
                foreach($categories["childrens"] as $child){
                    if(!isset($parent_cats[$child->id])){
                        $parent_cats[$child->id] = [];
                    }
                    $parent_cats[$child->id][] = $child->category_id; 
                }
            }
            $mydata["parent_cats"] = $parent_cats;
            if (!is_null($request->order) && $request->order === 'DESC') {
                return view("manager.pages.service.list")->with([
                    "list" => $mydata["list"],
                    "categories" => $mydata["categories"],
                    "parent_cats" => $mydata["parent_cats"]
                ]);
            }

            return $mydata;
        } else {
            if ($request->review_status && $request->review_status !== 'all') {
                $this->model = $this->model->where('review_status', '=', $request->review_status);
            } else {
                $this->model = $this->model->where('review_status', '=', 'published' );
            }

            if ($request->category && $request->category !== 'all') {
                $this->model = $this->model->whereHas('categories', function ($query) use ($request) {
                    $query->where('services_to_categories.category_id', $request->category);
                });
            }

            $twentyFourHoursAgo = now()->subHours(24);
            
            // $this->model = $this->model
            // ->leftJoin('services_phone_click_statistics', 'services.id', '=', 'services_phone_click_statistics.service_id')
            // ->leftJoin('services_statistics', 'services.id', '=', 'services_statistics.service_id')
            // ->selectRaw('services.*, COUNT(DISTINCT services_statistics.id) as views_count, COUNT(DISTINCT services_phone_click_statistics.id) as calls_count')
            // ->where('services_statistics.created_at', '<', now())
            // ->where('services_statistics.created_at', '>', $twentyFourHoursAgo)
            // ->groupBy('services.id');
            $this->model = $this->model
            ->leftJoin('services_phone_click_statistics', function ($join) use ($twentyFourHoursAgo) {
                $join->on('services.id', '=', 'services_phone_click_statistics.service_id')
                    ->whereBetween('services_phone_click_statistics.created_at', [$twentyFourHoursAgo, now()]);
            })
            ->leftJoin('services_statistics', function ($join) use ($twentyFourHoursAgo) {
                $join->on('services.id', '=', 'services_statistics.service_id')
                    ->whereBetween('services_statistics.created_at', [$twentyFourHoursAgo, now()]);
            })
            ->selectRaw('services.*, COALESCE(COUNT(DISTINCT services_statistics.id), 0) as views_count, COALESCE(COUNT(DISTINCT services_phone_click_statistics.id), 0) as calls_count')
            ->groupBy('services.id');


            //->orderBy('services.created_at', 'desc');

            // $this->model = $this->model
            // ->leftJoin('services_statistics', 'services.id', '=', 'services_statistics.service_id')
            // ->leftJoin('services_phone_click_statistics', 'services.id', '=', 'services_phone_click_statistics.service_id')
            // ->selectRaw('services.*, COUNT(DISTINCT services_statistics.id) as views_count, COUNT(DISTINCT services_phone_click_statistics.id) as calls_count')
            // ->groupBy('services.id');

            if($request->filter){
                $ordering = [$request->filter, $request->order];
            }
        }

        $mydata = parent::list($request, $ordering);
        $cats = DB::table('services_to_categories')->get();

        foreach ($mydata['list'] as $serv) {
            $new_cats = [];

            foreach ($cats as $cat) {
                if ($cat->services_id === $serv['id']) {
                    array_push($new_cats, $cat->category_id);
                }
            }

            $serv['category'] = $new_cats;
        }

        $page = 1;

        if (!is_null($request->page) && isset($request->page)) {
            $page = $request->page;
        }
        $parent_cats = [];
        foreach($mydata["categories"] as $categories){
            foreach($categories["childrens"] as $child){
                if(!isset($parent_cats[$child->id])){
                    $parent_cats[$child->id] = [];
                }
                $parent_cats[$child->id][] = $child->category_id; 
            }
        }
        $mydata["parent_cats"] = $parent_cats;
        
        if (!is_null($request->order) && $request->order === 'DESC') {
            return view("manager.pages.service.list")->with([
                "list" => $mydata["list"],
                "categories" => $mydata["categories"],
                "parent_cats" => $mydata["parent_cats"]
            ]);
        }

        return $mydata;
    }

    public function updateServiceName(Request $request) {
        $serv_id = $request->service_id;
        $serv_name = $request->name;

        $service = Services::find($serv_id);
        $service->service_name = $serv_name;
        $service->save();

        return redirect()->back()->with('success', 'Service name updated successfully.');
    }

    public function myApprovals(Request $request) {
        $user = Auth::user();

        if (!$user->hasRole('administrator')) {
            $this->model = $this->model->where('reviewer_id', $user->id);
        } else {
            $this->model = $this->model->where('review_status', '!=', 'started');
        }

        if ($request->user && intval($request->user) > 0){
            $this->model = $this->model->where('reviewer_id','=',$request->user);
        }


        $this->response['list'] = $this->model->orderBy('created_at','DESC')->paginate(10);

        $this->response['users'] = User::whereHas('roles', function($query){
            $query->where('name','!=','client');
        })->get();

        $this->response['pongBack'] = route('manager.services.service.my-approvals');

        return $this->responseView($this->viewData['my-approvals']);

    }

    public function form(Request $request)
    {
        $this->response['categories'] = Category::where('type','=','SPECIALIST')->where('isActive','=',true)->get()->toArray();

        if ($request->id) {
            $this->response['tags'] = Tags::whereHas('services',function ($query) use ($request){
                $query->where('service_id','=', $request->id);
            })->get()->toArray();

            $this->response['cities'] = City::where('is_active','=', true)->get()->toArray();

            $this->response['entity'] = $this->model->findOrFail($request->id);
            $reviews = ServiceReview::where( 'service_id','=',$this->response['entity']->id )->get();

            $this->response['reviewData'] = [
                0 => $reviews->count(),
                1 => $reviews->where('value',1)->count(),
                2 => $reviews->where('value',2)->count(),
                3 => $reviews->where('value',3)->count(),
                4 => $reviews->where('value',4)->count(),
                5 => $reviews->where('value',5)->count()
            ];
        }

        return $this->responseView($this->viewData['form']);
    }

    public function store(SavePostRequest $request)
    {
        $user = Auth::user();

        $serv = Services::findOrFail($request->id);
        $data = $request->except('_token');
        $data['is_active'] = true;

        $reviewer = Auth::user();
        if ($request->review_status){

            if ( $request->review_status != 'started' && $request->review_status !== $serv->review_status){
                $data['reviewer_id'] = $reviewer->id;

                if ($serv->reviewer_id == null){
                    $data['review_date'] = Carbon::now();
                }
            }
        }


        $data['priority'] = false;
        if ($request->priority == "on"){
            $data['priority'] = true;
        }

        $data['has_online_payment'] = false;
        if ($request->has_online_payment == "on"){
            $data['has_online_payment'] = true;
        }
        $data['has_serwish_quality'] = false;
        if ($request->has_serwish_quality == "on"){
            $data['has_serwish_quality'] = true;
        }

        $data['is_active'] = false;
        if ($request->is_active == "on"){
            $data['is_active'] = true;

        }

        if ($request->packet_id  && $request->packet_id  == 'none'){
            $data['packet_id'] = null;
        }

        $post = Services::updateOrCreate(['id'=>$request->id], $data );

        if ($request->files){
            $post->images()->syncWithPivotValues($request->media , ['other_entity'=>Services::class ]);
        }

        if ($request->category) {
            $post->categories()->sync($request->category);
        }

        if ($request->cities) {
            $post->cities()->sync($request->cities);
        }

        if ($request->tags){
            $tags = [];
            foreach ($request->tags as $t){

                $checkTag = Tags::find(intval($t));

//                echo '<pre>';
//                var_dump($request->tags, $checkTag==null);
//                exit;

                if ($checkTag == null){
                    $tag = Tags::create([
                        'is_active'      => true,
                        'user_id'        => $user->id,
                    ]);
                    $checkTag = TagsTranslation::create([
                        'name'      => $t,
                        'locale'    => 'ka',
                        'tags_id'=> $tag->id
                    ]);
                }
                $tags[] = $checkTag->tags_id;
            }
            $post->tags()->sync($tags);
        }


        if ($request->redirect) {
            $this->redirect = route('manager.dashboard',['edited'=>$post->id]);
        }
        if ($request->pongBack){
            $this->redirect = urldecode($request->pongBack);
        }
        return $this->responseJson(true, __('informationSaved'));
    }

    public function hasSerwishQuality($id, $has)
    {

        $service = Services::findOrFail($id);
        $service->has_serwish_quality = !(intval($has) == 0);
        $service->save();
        return back();
    }

    public function export(Request $request)
    {

        return Excel::download(new ServiceExport($request->exportIds), 'services.xlsx');
    }

    public function remove($id, Request $request)
    {
        return redirect()->to(urldecode($request->pongBack));

        $this->destroy($id);

        if ($request->pongBack){
            return redirect()->to(urldecode($request->pongBack));
        }
        return redirect()->route('manager.services.service.index', ['review_type'=>'started']);
    }


    function makeModel(): string
    {
        return Services::class;
    }
}
