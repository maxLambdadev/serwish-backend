<?php

namespace J3dyy\CrudHelper;


use Illuminate\Support\ServiceProvider;

class CrudHelperServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/resource/views/', 'crudHelper');
    }
}
