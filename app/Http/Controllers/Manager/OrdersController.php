<?php

namespace App\Http\Controllers\Manager;

use App\Exports\OrdersExport;
use App\Exports\UserExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\MakeIsCalledRequest;
use App\Http\Requests\MakeIsSeenRequest;
use App\Http\Requests\SaveCityRequest;
use App\Http\Requests\SavePostRequest;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\CallRequests;
use App\Models\City;
use App\Models\ContactRequests;
use App\Models\Orders\OrderGroupMessage;
use App\Models\Orders\OrderGroupPayment;
use App\Models\Orders\OrderGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class OrdersController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.orderPayments.form',
            'list'  => 'manager.pages.orderPayments.list',
            'show'  => 'manager.pages.orderPayments.show'
        ];

        parent::__construct();
    }

    public function list(Request $request, ?array $ordering = ['created_at', 'DESC'])
    {

        $this->model = $this->model->with('service','customer','specialist');

        if ($request->room_state && $request->room_state !== 'all'){
            $this->model = $this->model->where('room_state','=', $request->room_state);
        }
        return parent::list($request, $ordering);
    }

    public function export(Request $request)
    {
        return Excel::download(new OrdersExport($request->exportIds), 'orders.xlsx');
    }
    public function show($id)
    {
        $entity = OrderGroups::findOrFail($id);
        $this->response['entity'] = $entity;

        $this->response['messages'] = OrderGroupMessage::with('group.service','group.customer','group.specialist')
            ->where('order_groups_id', '=',$entity->id)
            ->orderBy('created_at','DESC')
            ->paginate(30);

        $this->response['payments'] = OrderGroupPayment::with('group.service','group.customer','group.specialist')
            ->where('order_groups_id', '=',$entity->id)
            ->orderBy('created_at','DESC')
            ->get();

        return $this->responseView($this->viewData['show']);
    }

    public function remove()
    {

    }

    function makeModel(): string
    {
        return OrderGroups::class;
    }
}
