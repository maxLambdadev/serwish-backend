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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactRequestsController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.contactRequests.form',
            'list'  => 'manager.pages.contactRequests.list'
        ];
        parent::__construct();
    }

    public function list(Request $request, ?array $ordering = ['created_at', 'DESC'])
    {
        $this->model = $this->model->orderBy('seen','ASC');
        return parent::list($request, $ordering); // TODO: Change the autogenerated stub
    }

    public function isSeen(MakeIsSeenRequest $request)
    {
        $callRequest = ContactRequests::findOrFail($request->contact_request_id);

        $callRequest->seen = true;
        $callRequest->save();

        $this->redirect = route('manager.contact-requests.list');

        return $this->responseJson(true, __('informationSaved'));
    }

    function makeModel(): string
    {
        return ContactRequests::class;
    }
}