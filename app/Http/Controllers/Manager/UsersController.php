<?php

namespace App\Http\Controllers\Manager;

use App\Exports\ServiceExport;
use App\Exports\UserExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateUserRequest;
use App\Jobs\SendSmsJob;
use App\Models\Blog\Category;
use App\Models\Orders\OrderGroupPayment;
use App\Models\Orders\OrderGroups;
use App\Models\ServiceReview;
use App\Models\Services;
use App\Models\SpecialistCommentReview;
use App\Models\SpecialistReview;
use App\Models\User;
use Illuminate\Http\Request;
use J3dyy\SmsOfficeApi\SmsClient;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;


class UsersController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
           'form'   => 'manager.pages.users.form',
           'list'   => 'manager.pages.users.list',
           'show'   => 'manager.pages.users.show',
           'contacts'   => 'manager.pages.users.contacts'
        ];

        parent::__construct();
    }

    public function form(Request $request)
    {
        $this->response['roles'] = Role::all();
        return parent::form($request);
    }


    public function store(CreateUserRequest $request)
    {

        $user = new User();

        if ($request->id != null){
            $user = User::findOrFail($request->id);
        }else{
            $user->email = $request->email;
        }

        $user->name = $request->name;
        if ($request->password){
            $user->password = bcrypt($request->password);
        }

        $user->id_number = $request->id_number;
        $user->date_of_birth = $request->date_of_birth;
        $user->phone_number = $request->phone_number;


        if ($request->personal == "on"){
            $user->personal = "personal";
        }else{
            $user->personal = "business";
        }

        if ($request->client_type == "on"){
            $user->client_type = "employee";
        }else{
            $user->client_type = "client";
        }

        $user->save();

        if ($request->role_id){
            $user->roles()->detach();

            $role = Role::find($request->role_id);
            if ($role !== null){
                $user->assignRole($role);
            }
        }

        return response()->json([
            'success'=>true,
            'message'=>'User Created',
            'redirect'=>route('manager.users.index')
        ]);
    }

    public function show(int $id, Request $request)
    {
        $this->response['user'] = User::with('balance')->findOrFail($id);
        $this->response['services'] = Services::where('user_id','=',$id);
        if ($request->title) {
            $this->response['services'] = $this->response['services']->whereHas('translations',function ($query) use ($request){
                $query->where('title','like','%'.$request->title.'%');
            });
        }
        $this->response['services'] =  $this->response['services']->paginate(10);

        $this->response['orders'] = OrderGroups::with('service','customer','specialist')
            ->orderBy('created_at','DESC')
            ->paginate(20);


        $reviews = SpecialistReview::where( 'user_id','=',$this->response['user']->id )->get();
        $this->response['reviewData'] = [
            1 => $reviews->where('value',1)->count(),
            2 => $reviews->where('value',2)->count(),
            3 => $reviews->where('value',3)->count(),
            4 => $reviews->where('value',4)->count(),
            5 => $reviews->where('value',5)->count()
        ];


        return view($this->viewData['show'],$this->response);
    }

    public function export(Request $request)
    {
        return Excel::download(new UserExport($request->exportIds), 'users.xlsx');
    }

    public function changeSpecialistState(Request $request)
    {

        $user = User::findOrFail($request->user_id);
        $user->client_type = 'client';
        if ($request->state == 'true'){
            $user->client_type = 'employee';
        }
        $user->save();
        return repsonse()->json([
            'sucess' => true
        ]);
    }

    public function contacts(Request $request)
    {
        //todo
        $this->response['users'] = User::orderBy('id','DESC')->get();
        $this->response['contacts'] = User::paginate(40);
        $this->response['categories'] = Category::where('type','SPECIALIST')->get();

        return view($this->viewData['contacts'], $this->response);
    }

    public function sendSms(Request $request){

        //single message
        if ($request->smsType == "default"){

            if (!$request->message || !$request->userId){
                return abort(400,'ყველა ველი სავალდებულოა');
            }

            $user = User::findOrFail($request->userId);

            if ($user->phone_number != null && $user->phone_number != "") {
                SmsClient::instance()
                    ->to($user->phone_number)
                    ->message($request->message)
                    ->send();
            }
        }
        else if ($request->smsType == "employee" || $request->smsType == "client"){
            $users = User::where('client_type', $request->smsType)
                ->where('phone_number', '!=', null)
                ->get();

            foreach ($users as $user){
                    SendSmsJob::dispatch($user->phone_number, $request->message);
            }

            return response()->json([
                'success'=>true
            ]);
        }
    }

    public function sendCustomSms(Request $request){

        if ($request->users && is_array($request->users)){
            foreach ($request->users as $u){
                $user = User::findOrFail($u);
                SendSmsJob::dispatch($user->phone_number, $request->message);

            }
        }

        return response()->json([
            'success'=>true
        ]);
    }

    public function getUsersByCategories(Request $request)
    {
        $users = User::whereHas('services', function ($query) use ($request){
            if ($request->serwishQ == 'on'){
                $query->where('has_serwish_quality','=',true);
            }

            $query->whereHas('categories',function ($q) use ($request){
                $q->where('category.id',$request->category);
            });
        })->groupBy('id');


        return response()->json([
           'users'=>$users->get()
        ]);
    }

    function makeModel(): string
    {
        return User::class;
    }
}
