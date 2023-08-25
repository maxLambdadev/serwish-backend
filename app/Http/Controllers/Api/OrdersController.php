<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CreatePaymentRequest;
use App\Http\Requests\Api\CreateWithdrawalRequest;
use App\Http\Requests\Api\GetGroupPaymentsRequest;
use App\Http\Requests\Api\GetOrdersRequest;
use App\Http\Requests\Api\StartOrderRequest;
use App\Http\Requests\MediaStoreRequest;
use App\Models\Orders\OrderGroupMessage;
use App\Models\Orders\OrderGroupPayment;
use App\Models\Orders\OrderGroups;
use App\Models\ServicePacket;
use App\Models\PayablePacket;
use App\Models\Resource;
use App\Models\Services;
use App\Models\SpecialistCommentReview;
use App\Models\UserBalance;
use App\Models\UserWithdrawalRequests;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use J3dyy\BogPaymentWrapper\Request\BogRequest;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Gumlet\ImageResize;

/**
 *  Class ServiceController
 *  @package App\Http\Controllers\Api
 *  @author jedy
 */
class OrdersController extends ApiController
{

    public function packetList(Request $request)
    {
        $packets = PayablePacket::where('is_active','=',true)->orderBy('priority','desc')->get();

        $packets->map(function ($item){
            $mainImage = $item->images()->wherePivot('is_active','=',true)->first();
            $item['mainPicture'] = $mainImage == null ? $item->images()->first() : $mainImage;
        });

        return $this->response(200,$packets);
    }

    /**
     * @OA\Post(
     *     tags={"Orders"},
     *     path="/api/services/orders/start-order",
     *     summary="start first order ",
     *     operationId="postStartORder",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/StartOrderRequest")
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
    public function startOrder(StartOrderRequest $request)
    {
        try {
            $service = Services::where('review_status','=','published')->findOrFail($request->service_id);
            $user = Auth::user();

            $checkGroup = OrderGroups::where('customer_id','=',$user->id)
                ->where('room_state','!=','order_closed')
                ->where('service_id','=',$service->id)->first();

            if ($checkGroup == null){
                $checkGroup = OrderGroups::create([
                    'name'=>$service->title,
                    'room_state'=>'started',
                    'customer_id'=>$user->id,
                    'specialist_id'=>$service->user_id,
                    'service_id' => $service->id
                ]);
            }

            return $this->response(200,  $checkGroup);

        }catch (InvalidArgumentException | \Illuminate\Database\QueryException $e){
            return $this->response(200, error: $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     tags={"Orders"},
     *     path="/api/services/orders/get-orders/list",
     *     summary="Get orders ",
     *     operationId="getOrdersByUser",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/GetOrdersRequest")
     *      ),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Services")
     *         ))
     *      ),
     * )
     */
    public function getOrders(GetOrdersRequest $request){
        $user = Auth::user();

        $groups = OrderGroups::with('service.images');

        if ($request->specialist){
            $groups = $groups->with('customer.images')->where('specialist_id','=',$user->id);
        }else{
            $groups = $groups->with('specialist.images')->where('customer_id','=',$user->id);
        }

        if ($request->type){
            $groups = $groups->where('room_state','=',$request->type);
        }else{
//            $groups = $groups->where('room_state','!=','order_closed');
        }
        $groups = $groups->orderBy('room_state','ASC');

        $groups = $groups->orderBy('created_at')->paginate($request->perPage ? $request->perPage : 10);

        return $this->response(200, $groups);
    }


    /**
     * @OA\Get(
     *     tags={"Orders"},
     *     path="/api/services/orders/get-order/{id}",
     *     summary="Get orders ",
     *     operationId="getSingleOrderById",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/GetOrdersRequest")
     *      ),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Services")
     *         ))
     *      ),
     * )
     */
    public function getOrder($id){
        $user = Auth::user();
        $groups = OrderGroups::with('specialist.images','service.images');


        $groups = $groups->orderBy('created_at')->find($id);

        if ($groups !== null){
            $reviews = SpecialistCommentReview::where('service_id','=', $groups->service_id)
                ->where('user_id','=',$groups->customer_id)->orderBy('created_at','DESC')->first();
            $groups->user_review = $reviews;
        }

        return $this->response(200, $groups);
    }

    /**
     * @OA\Post(
     *     tags={"Orders"},
     *     path="/api/services/orders/create-payment",
     *     summary="create payment for group ",
     *     operationId="createPaymentRequest",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/CreatePaymentRequest")
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
    public function createPaymentRequest(CreatePaymentRequest $request){
        $user = Auth::user();
        // todo check if user already paied for this group
        $bog = new BogRequest();

        $checkService = Services::find($request->service_id);
        $checkPacket = PayablePacket::find($request->packet_id);

        if ($checkService !== null && $checkPacket !== null){

            $paymentModel = ServicePacket::create([
                'amount'=> $checkPacket->price,
                'locale'=>'ka',
                'service_id'=>$checkService->id,
                'user_id'=> $user->id,
                'payable_packet_id'=> $checkPacket->id
            ]);

            $payment = ServicePacket::with('service')->findOrFail($paymentModel->id);

//            $paymentRequest = $bog->startOrder([$payment], $payment->id);
            $paymentRequest = $bog->startOrder($checkPacket->id, $payment->service_id);


            $payment->order_id = $paymentRequest->order_id;
            $payment->payment_hash = $paymentRequest->payment_hash;
            $payment->redirect_url = $paymentRequest->links[1]->href;
            $payment->save();

            return $this->response(200, $payment->redirect_url);
        }

        return $this->response(400, error: 'Service not exists');
    }

    /**
     * @OA\Get(
     *     tags={"Orders"},
     *     path="/api/services/orders/get-payments/list",
     *     summary="Get payment list ",
     *     operationId="getPayments",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/GetGroupPaymentsRequest")
     *      ),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Services")
     *         ))
     *      ),
     * )
     */
    public function getPayments(GetGroupPaymentsRequest $request){
        $user = Auth::user();

        $payments = OrderGroupPayment::where('order_groups_id','=',$request->group_id);
        if ($request->type){
            $payments->where('type','=',$request->type);
        }

        $payments = $payments->orderBy('created_at','DESC')->paginate($request->perPage ? $request->perPage : 10);

        return $this->response(200, $payments);
    }

    public function messages(Request $request){
        $user = Auth::user();

        $messages = OrderGroupMessage::where('order_groups_id','=', $request->group_id)
            ->orderBy('id','DESC')
            ->paginate(20);

        return response()->json([
            "messages"=>$messages
        ]);
    }

    /**
     * @OA\Get(
     *     tags={"Orders"},
     *     path="/api/services/orders/order/unread-messages",
     *     summary="Get unread message list ",
     *     operationId="getUnreadMessages",
     *     @OA\Response(response="201",
     *        description="success",
     *      ),
     * )
     */
    public function unreadMessages(Request $request){
        $user = Auth::user();

        $orders = OrderGroups::where('specialist_id','=',$user->id)
            ->where('room_state','!=','order_closed')->get();

        $tasks = OrderGroups::where('customer_id','=',$user->id)
            ->where('room_state','!=','order_closed')->get();

        $orderUnreadMessages = OrderGroupMessage::where('sender_id','!=',$user->id)
            ->where('seen','=',false)
            ->whereIn('order_groups_id',$orders->pluck('id'))->get();

        $tasksUnreadMessages = OrderGroupMessage::where('sender_id','!=',$user->id)
            ->where('seen','=',false)
            ->whereIn('order_groups_id',$tasks->pluck('id'))->get();

        $orderUnreadCount = [];
        $taskUnreadCount = [];

        foreach ($orderUnreadMessages as $order){
            if (!isset($orderUnreadCount[$order->order_groups_id])){
                $orderUnreadCount[$order->order_groups_id] = 1;
            } else{
                $orderUnreadCount[$order->order_groups_id]++;
            }
        }

        foreach ($tasksUnreadMessages as $task){
            if (!isset($taskUnreadCount[$task->order_groups_id])){
                $taskUnreadCount[$task->order_groups_id] = 1;
            } else{
                $taskUnreadCount[$task->order_groups_id]++;
            }
        }



        return $this->response(200, [
            "orderUnread" => $orderUnreadCount,
            "taskUnread" => $taskUnreadCount
        ]);
    }


     /**
     * @OA\Get(
     *     tags={"Orders"},
     *     path="/api/services/orders/my-payment/my-withdrawals",
     *     summary="Get unread message list ",
     *     operationId="userWithdrawals",
     *     @OA\Response(response="201",
     *        description="success",
     *      ),
     * )
     */
    public function userWithdrawals(Request $request){
        $user = Auth::user();

        $userPaymentRequestSum = UserWithdrawalRequests::where('user_id','=',$user->id);

        if ($request->status){
            $userPaymentRequestSum = $userPaymentRequestSum->where('status','in_progress');
        }
        $userPaymentRequestSum = $userPaymentRequestSum->orderBy('created_at','DESC')->paginate(10);

        return $this->response(200, $userPaymentRequestSum);
    }



    /**
     * @OA\Post(
     *     tags={"Orders"},
     *     path="/api/services/orders/request-withdraw",
     *     summary="create payment for group ",
     *     operationId="requestWithdrawal",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/CreateWithdrawalRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserWithdrawalRequests")
     *         ))
     *      ),
     * )
     */
    public function requestWithdrawal(CreateWithdrawalRequest $request){
        $user = Auth::user();
        $balance = UserBalance::where('user_id','=',$user->id)->first();

        if ($user->client_type !== 'employee') return $this->response(400, error: "user not specialist");

        if ($balance == null) {
            $balance = UserBalance::create([
                'user_id' => $user->id,
                'balance'  => 0,
            ]);
        }

        //count in_progress transaction
        //double spent check
        $userPaymentRequestSum = UserWithdrawalRequests::where('user_id','=',$user->id)
        ->where('status','in_progress')->sum('amount');


        $amount = doubleval($request->amount);
        $sum = number_format($userPaymentRequestSum + $amount,2);

        if ( $balance->balance >= $sum ){
            $withdrawal = UserWithdrawalRequests::create([
                'user_id'=>$user->id,
                'status'=>'in_progress',
                'amount'=>number_format($amount,2),
                'fullname'=>$request->fullname,
                'iban'=>$request->iban
            ]);
            return $this->response(200, $withdrawal);
        }
        return $this->response(400, error: "insufficient amount your balance is $balance->balance ,requested amount with all past withdrawals waiting approves greater than your balance ");
    }


    /**
     * @OA\Post(
     *     tags={"Orders"},
     *     path="/api/services/orders/order/message/attachment",
     *     summary="create orders for group ",
     *     operationId="uploadMessageAttachment",
     *      @OA\Parameter (
     *          required=true,
     *          name="filterable",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/MediaStoreRequest")
     *      ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201",
     *        description="success",
     *        @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/OrderGroupMessage")
     *         ))
     *      ),
     * )
     */
    public function uploadMessageAttachment(MediaStoreRequest $request)
    {
        $user = Auth::user();
        Validator::make($request->all(),[
            'group_id'   => 'required',
        ])->validate();


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

            $group = OrderGroups::findOrFail($request->group_id);
            $message = OrderGroupMessage::create([
                'order_groups_id'=>$group->id,
                'sender_id'=>$user->id,
                'message'=> $media->path,
                'type'=>'attachment',
                'sender'=> $group->customer_id == $user->id ? 'customer' : 'specialist'
            ]);


            $client = new \GuzzleHttp\Client();

            try{

                $resp = $client->request('POST',"https://ws.serwish.ge/uploaded-chat-image", [
                    'json'=> [
                        'group'=> $group,
                        'message'=> $media->path,
                        'from'=>$user->name,
                    ]
                ]);
            }catch (RequestException $r){
                dd($r->getResponse()->getBody()->getContents());
            }


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

            return response()->json([
                'group' => $group,
                'message'=>$message
            ]);
        }
        return response("file doesn't exists", 400);
    }

    public function findPaymentByHash(Request $request)
    {
        $payment = OrderGroupPayment::with('group')->where('payment_hash','=',$request->p_hash_mash_smash)->first();

        return $payment;
    }

}
