<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;
use J3dyy\SmsOfficeApi\Config;

class ConfigurationController extends Controller
{

    public function smsOfficeBasic()
    {
        $conf = Configuration::where('key','=','SMS_OFFICE_BASIC')->first();
        if ($conf != null){
            $conf = json_decode($conf->data);
        }

        $balanceResponse =\J3dyy\SmsOfficeApi\SmsClient::instance()->balance();
        $balance = $balanceResponse->getBody()->getBalance();
//        $balance = 5000;

        return view('manager.pages.configuration.smsoffice', ['entity'=>$conf, 'balance'=>$balance]);
    }

    public function smsOfficeBasicSave(Request $request)
    {
        $conf = Configuration::where('key','=','SMS_OFFICE_BASIC')->first();

        if ($conf == null) {
            $conf = new Configuration();
            $conf->key = 'SMS_OFFICE_BASIC';
        }

        $data = $request->except('_token','redirect');
        $conf->data = json_encode($data);
        $conf->save();
        return back();

    }

}
