<?php

namespace J3dyy\CrudHelper;

use Carbon\Exceptions\InvalidCastException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use J3dyy\CrudHelper\Components\ActionType;
use J3dyy\CrudHelper\Components\Form\Form;
use J3dyy\CrudHelper\Helpers\Controller\InteractsToLayout;
use J3dyy\CrudHelper\View\Viewable;

abstract class CrudController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, InteractsToLayout ;

    protected $columns = [];

    protected $model = null;

    public function __construct(array $columns = [])
    {
        $model = $this->model();
        $this->model = new $model();
        if (!$this->model instanceof Model){
            throw new InvalidCastException(sprintf('the provided model %s not instance of Illuminate\Database\Eloquent\Model ', $model));
        }
        $this->columns = $columns;
    }

    //todo
    protected function getColumns(): array{
        return $this->columns;
    }

    /**
     * todo
     */
    protected function getList(): JsonResponse {
        return response()->json([
            'columns' => [ 'id', 'name' ]
        ]);
    }


    public function show(Request $request){
        $entity = $this->model->findOrFail($request->id);
        return response()->json([
           'success'    => true,
           'entity'     => $entity,
           'form'       => $this->fillForm($request)
        ]);
    }

    public function store(Request $request){
        $action = ActionType::INSERT;

        if ($request->id){
            $action = ActionType::UPDATE;
            $entity = $this->model->findOrFail($request->id);
            $entity->update($request->all());
        }else{
            $entity = $this->model->create($request->except('id','_token'));
        }

        return [
            'action'=>$action,
            'entity'=>$entity
        ];
    }

    public function delete( $id ) {
        $this->model->findOrFail($id)->delete();
        return response()->json([
            'success'   => true,
        ]);
    }

    protected function render(Viewable $layout)
    {
        return $layout->render();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getForm(Request $request): JsonResponse {
        return response()->json([
            'form' => $this->fillForm($request)
        ]);
    }

    abstract function fillForm(Request $request): Form;

    abstract function model(): string;
}
