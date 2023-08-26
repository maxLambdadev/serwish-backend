<?php
namespace App\Http\Controllers\Manager\Services;

use App\Exports\ServiceCategoryExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SaveBlogCategoryRequest;
use App\Models\Blog\Category;
use App\Models\Blog\CategoryBack;
use App\Models\CallRequests;
use App\Models\Services;
use App\Models\Tags;
use App\Models\TagsTranslation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class ServiceCategoryController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.service.category.form',
            'list'  => 'manager.pages.service.category.list',
            'statistic'  => 'manager.pages.service.category.statistic',
        ];

        parent::__construct();
    }

    public function form(Request $request)
    {
        $tags = Tags::orderBy('created_at','DESC')->paginate(800);

        $this->response['categories'] = Category::with('tags')->where('type','=','SPECIALIST')->orderBy('id');
        if ($request->id) {
            $this->response['categories'] = $this->response['categories']->where('id','!=',$request->id);
        }


        $this->response['categories'] = $this->response['categories']->get();
        $this->response['tags'] = $tags;
        return parent::form($request);
    }

    public function list(Request $request , ?array $ordering = ['created_at','DESC'])
    {
        $this->model = $this->model->where('type','SPECIALIST');
        return parent::list($request);
    }

    public function statistics(Request $request)
    {
        $date = str_replace('+',' ', $request->daterange);
        $dates = explode(' - ',$date);
        $startDate = (!empty($request->daterange)) ? Carbon::parse($dates[0]) : Carbon::now()->startOfDay();
        $endDate = (!empty($request->daterange)) ?  Carbon::parse($dates[1])->addDay() : Carbon::now()->endOfDay();

        $this->model = $this->model
            ->with('childrens')
            ->where('category.type','SPECIALIST')
            ->where('category.isActive','=',true)
            ->where('category.category_id','=',null)
//            ->orderBy('stat_back_count','DESC')
            ->get();


        $this->model->map(function ($item) use ($startDate,$endDate){

            $categoryIds = [ $item->id ,...$item->childrens->pluck('id')];

            $viewCount = CategoryBack::whereIn('category_id', $categoryIds)->whereBetween('created_at',[$startDate,$endDate]);
            $item['stat_back_count'] = $viewCount->count();

            $callRequestCount = CallRequests::whereIn('category_id', $categoryIds)->whereBetween('created_at',[$startDate,$endDate]);
            $item['call_requests_count'] = $callRequestCount->count();

            $serviceCount = $item->services()->whereBetween('created_at',[$startDate,$endDate]);

            $item['services_count'] = $serviceCount->count();

            if ($item->has('childrens')){
                $item->childrens->map(function ($i) use ($startDate, $endDate, $item){

                    $viewCount = CategoryBack::where('category_id', $i->id)->whereBetween('created_at',[$startDate,$endDate]);
                    $i['stat_back_count'] = $viewCount->count();

                    $callRequestCount = CallRequests::where('category_id', $i->id)->whereBetween('created_at',[$startDate,$endDate]);
                    $i['call_requests_count'] = $callRequestCount->count();

                    $serviceCount = $i->services()->whereBetween('created_at',[$startDate,$endDate]);
                    $i['services_count'] = $serviceCount->count();
                    $item['services_count'] += $i['services_count'];

                });
            }


        });

        $this->response['list'] = $this->model->sortBy('stat_back_count')->toArray();

        $keys = array_column($this->response['list'], 'childrens');
        array_multisort($keys, SORT_DESC, $this->response['list']);

        return $this->responseView($this->viewData['statistic']);
    }


    public function store(SaveBlogCategoryRequest $request)
    {
        $data = $request->except('_token','id');
        $data['type'] = 'SPECIALIST';
        $data['isActive'] = false;
        $data['author_id'] = Auth::user()->id;

        if ($request->isActive == "on"){
            $data['isActive'] = true;
        }
        if ($request->blog_position){
            $data["blog_position"] = $request->blog_position;
        }
        if ($request->redirect) {
            $this->redirect = $request->redirect;
        }

        if ($request->id){
            $category = Category::updateOrCreate(['id'=>$request->id], $data );
        }else{
            $category = Category::create($data );
        }

        $user = Auth::user();
        if ($request->tag_id){
            $tags = [];
            foreach ($request->tag_id as $tag){

                $checkTag = TagsTranslation::where('name','=',$tag)
                    ->where('locale','=','ka')
                    ->first();

                if ($checkTag == null){
                    $newTag = Tags::create([
                        'is_active'      => true,
                        'user_id'        => $user->id,
                    ]);
                    $checkTag = TagsTranslation::create([
                        'name'      => $tag,
                        'locale'    => 'ka',
                        'tags_id'=> $newTag->id
                    ]);
                }
                $tags[] = $checkTag->tags_id;
            }
            $category->tags()->sync($tags);
        }

        if ($request->media){
            $category->images()->detach();
            $category->images()->attach((int) $request->media , [ 'other_entity'=>Category::class ]);
        }

        return $this->responseJson(true, __('informationSaved'));
    }


    public function export(Request $request)
    {
        return Excel::download(new ServiceCategoryExport($request->exportIds), 'categories.xlsx');

    }


    function makeModel(): string
    {
        return Category::class;
    }
}


