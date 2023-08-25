<?php

namespace J3dyy\BogPaymentWrapper;


use Illuminate\Support\ServiceProvider;

class BogPaymentWrapperServiceProvider extends ServiceProvider
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
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/bog-payment.php' => config_path('bog-payment.php')
        ],'bog-payment-wrapper');

        $this->loadViewsFrom(__DIR__ . '/resource/views/', 'bogPaymentWrapper');
    }
}
