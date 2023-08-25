<?php
namespace J3dyy\BogPaymentWrapper\Request;



use App\Models\PayablePacket;
use App\Models\Services;

class BogRequest
{
    protected $client;

    protected $response;


    protected $headers = [
        'Content-Type' => 'application/x-www-form-urlencoded',
        'Authorization' => 'Basic MTExMjA6NTI5ODg0OGY3MjQzODIxOWUxZjVkMzA2MDBhYWNlZDk='
    ];



    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri'=> config('bog-payment.payment_api_uri')
        ]);
    }

    public function token()
    {
        $this->response = $this->postRequest('/opay/api/v1/oauth2/token',[
            'form_params'=>[
                'grant_type'=>'client_credentials'
            ]
        ]);
        $resp = json_decode($this->response->getBody()->getContents());

        return $resp->access_token;
    }

    public function getOrder(string $orderId){
        $token = $this->token();

        $this->tokenHeaders($token);

        $params = [];

        $this->response = $this->getRequest('/opay/api/v1/checkout/payment/'.$orderId);

        $this->response = json_decode($this->response->getBody()->getContents());

        return $this->response;
    }

    public function startOrder($packetId, $serviceId){
        $token = $this->token();

        $this->tokenHeaders($token);

        $packet = PayablePacket::findOrFail($packetId);
        $product = Services::findOrFail($serviceId);

        $params = [
            'body'=>[
                'items'=>[],
                'locale'=>'ka',
                'intent'=>'CAPTURE',
                'redirect_url'=>'https://api.serwish.ge/payment/callbac?p_id='.$serviceId,
                'purchase_units'=>[
                    [
                        'amount'=>[
                            'currency_code'=>'GEL',
                            'value'=> $packet->price
                        ],
                    ]
                ]
            ]
        ];


        $params['body']['items'][] = [
            'amount'=>$packet->price,
            'description'=>$product->title,
            'quantity'=>1,
            'product_id'=> $product->id,
        ];
        $params['body']['purchase_units'][0]['amount']['value'] += $product->amount;

        $params['body'] = json_encode($params['body']);

        $this->response = $this->postRequest('/opay/api/v1/checkout/orders',$params);

        $this->response = json_decode($this->response->getBody()->getContents());

        return $this->response;
    }


    private function tokenHeaders($token){
        $this->headers['Authorization'] = "Bearer  $token";
        $this->headers['Content-Type'] = "application/json";
    }

    //todo
    private function postRequest($endpoint, $params = []){
        return $this->client->request('POST',$endpoint, [
            'headers'=> $this->headers,
            ...$params
        ]);
    }
    private function getRequest($endpoint){
        return $this->client->get($endpoint, [
            'headers'=> $this->headers,
        ]);
    }


}
