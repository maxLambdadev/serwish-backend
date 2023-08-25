<?php

namespace App\Http\Controllers;

use App\Models\League\Teams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use J3dyy\LaravelLocalized\DB\Localized;

abstract class BaseController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $model;

    protected $request;

    protected $redirect = null;

    protected $response = [];

    protected $listPagination = 20;

    protected $viewData = [
        'list'  => '',
        'form'  => ''
    ];

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @description  Reflect Eloquent Model if incorrect cast throwing exception
     */
    public function __construct()
    {
        $this->model = app()->make($this->makeModel());
        if (!$this->model instanceof Model) {
            throw new \Exception("model must be extend Eloquent\Model");
        }
    }

    public function list(Request $request , ?array $ordering = ['created_at','DESC']){
        $list = $this->model;

        //default name search support
        if ($request->name) {
            if ($this->model instanceof Localized) {
                $list = $this->model->whereHas('translations',function ($query) use ($request){
                    $query->where('name','like','%'.$request->name.'%');
                });
            }else{
                $list = $list->where('name','like','%'.$request->name.'%');
            }
        }

        if ($request->title) {
            if (intval($request->title) > 1){
                $list =  $this->model->where('services.id', $request->title);
            }else{
                $list = $this->model->whereHas('translations',function ($query) use ($request){
                    $query->where('title','like','%'.$request->title.'%');
                });
            }
        }
        
        if ($ordering != null){
            $list = $list->orderBy($ordering[0],$ordering[1]);
        }
        
        $page = 1;

        if (!is_null($request->page) && isset($request->page)) {
            $page = $request->page;
        }

        //$twentyFourHoursAgo = now()->subHours(24);
        
        $this->response['list'] = $list
        //->whereBetween('services_statistics.created_at', [$twentyFourHoursAgo, now()])
        ->paginate($this->listPagination, ['*'], 'page', $page);
        return $this->responseView($this->viewData['list']);
    }

    public function form(Request $request){
        if ($request->id) {
            $this->response['entity'] = $this->model->findOrFail($request->id);
        }
        return $this->responseView($this->viewData['form']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id){
        $this->delete($id);
        return $this->responseJson(true, __('itemRemoved'), [
            'ids'=>[$id]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete(Request $request)
    {
        if ($request->ids && is_array($request->ids))
        {
            foreach ($request->ids as $id){
                $this->delete($id);
            }
        }

        return $this->responseJson(true, __('itemRemoved'), [
            'ids'=>$request->ids
        ]);
    }

    /**
     * @param $id
     */
    private function delete($id){
        $entity = $this->model->findOrFail($id);
        //check resource relation and detach
        if (method_exists($entity,'images')) {
             $entity->images()->detach();
        }
        $entity->destroy($id);
    }

    /**
     * @param string $view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function responseView(string $view){
        return view($view,$this->response);
    }

    /**
     * @param bool $success
     * @param string $message
     * @param array $body
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseJson(bool $success,string $message, array $body = []){

        $this->buildJsonResponse($success,$message,$body);
        return response()->json($this->response);
    }

    /**
     * @param bool $success
     * @param string $message
     * @param string|null $body
     */
    private function buildJsonResponse(bool $success,string $message, array $body = []): void{
        $this->response['success'] = $success;
        $this->response['message'] = $message;
        $this->response['body'] = $body;
        if ($this->redirect != null) {
            $this->response['redirect'] = $this->redirect;
        }
    }

    /**
     * @return string
     * @description Reflect Eloquent Model
     */
    abstract function makeModel(): string;
}
