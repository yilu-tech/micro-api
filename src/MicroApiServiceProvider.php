<?php

namespace YiluTech\MicroApi;

use Illuminate\Support\ServiceProvider;


class MicroApiServiceProvider extends ServiceProvider
{


    public function boot(){
        $this->publishes([
            __DIR__ . '/../config/micro.php' => config_path('micro.php')
        ]);
    }
    /**
     * 在容器中注册绑定。
     *
     * @return void
     */
    public function register()
    {
        if(!class_exists('\MicroApi')){
            class_alias(MicroApiFacade::class,'MicroApi');
        }

        $this->app->singleton("MicroApi", function ($app) {
            return new MicroApiManager($app);
        });
       

    }
}