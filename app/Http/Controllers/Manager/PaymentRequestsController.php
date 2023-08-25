<?php

namespace App\Http\Controllers\Manager;

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
use App\Models\UserBalance;
use App\Models\UserWithdrawalRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentRequestsController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.withdrawals.form',
            'list'  => 'manager.pages.withdrawals.list'
        ];

        parent::__construct();
    }

    public function list(Request $request, ?array $ordering = ['created_at', 'DESC'])
    {
        $this->model = $this->model->orderBy('created_at','DESC');
        return parent::list($request, $ordering);
    }

    public function changeStatus(Request $request)
    {
        $callRequest = UserWithdrawalRequests::findOrFail($request->id);
        $balance = UserBalance::where('user_id','=',$callRequest->user_id)->first();

        $callRequest->status = $request->status;

        if ($request->status == 'approved'){
//            $newAmount = $balance->balance - $callRequest->amount;
//            if ($newAmount > $balance->balance){
//                $balance->balance = 0;
//            }else{
                $balance->balance = $balance->balance - $callRequest->amount;
                $balance->save();
//            }
           
        }

        $callRequest->save();

        $this->redirect = route('manager.payment-requests.list');

        return $this->responseJson(true, __('informationSaved'));
    }

    function makeModel(): string
    {
        return UserWithdrawalRequests::class;
    }
}
