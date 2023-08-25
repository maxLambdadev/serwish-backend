<?php

namespace App\Providers;

use App\Models\CallRequests;
use App\Models\Configuration;
use App\Models\ContactRequests;
use App\Models\Locales;
use App\Models\Services;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use J3dyy\SmsOfficeApi\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @important todo refactor
     */
    public function boot()
    {
        Paginator::useBootstrap();

        try{
            //set default language
            $locales = Locales::all();
            $locale = $locales->where('is_default','=',true)->first();
            $locales = $locales->toArray();

            if (Configuration::get_config('SMS_OFFICE_BASIC')) {
                Config::apiKey(Configuration::get_config('SMS_OFFICE_BASIC')->apiKey);
            }

        }catch(QueryException $queryException){

            $locale = new Locales([
                'name'              =>  config('app.locale') ,
                'original_name'     =>  config('app.locale'),
                'iso_code'          =>  config('app.locale'),
                'is_active'         =>  true,
                'is_default'        =>  true,
            ]);
            $locales = [$locale];
        }

        if ($locale != null){
            App::setLocale( $locale->iso_code );
            view()->share('locales',$locales);
            view()->share('defaultLocale',$locale);
        }

        \J3dyy\SmsOfficeApi\Config::apiKey('f7cdabb7b1104cf8a55716afaa0a8ea2')->sender('serwish');

        Blade::componentNamespace('App\\View\\Components\\Form','components');

        //if we in admin panel
        if (str_contains($this->app->request->getRequestUri(),'/panel')){
            $callRequest = CallRequests::where('is_called','=',false)->orderBy('is_called','ASC')
                ->orderBy('id','DESC')->limit(8)->get();
            view()->share('callReqs', $callRequest);

            $contactRequest = ContactRequests::where('seen','=',false)->orderBy('seen','ASC')
                ->orderBy('created_at','DESC')->limit(8)->get();
            view()->share('contactReqs', $contactRequest);

            $newServiceModel = Services::where('review_status','=','started')
                ->orderBy('created_at','DESC');

            $newServicesCount = $newServiceModel->count();

            $newServices = $newServiceModel->limit(10)->orderBy('created_at','DESC')->get();

            view()->share('newServicesCount',$newServicesCount);
            view()->share('newServices',$newServices);
        }



    }
}
