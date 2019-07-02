<?php

namespace YiluTech\MicroApi;

use Illuminate\Support\ServiceProvider;
use YiluTech\MicroApi\Transaction\MicroTransactionFacade;
use YiluTech\MicroApi\Transaction\MicroTransactionManager;


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


        if(!class_exists('\MicroTransaction')){
            class_alias(MicroTransactionFacade::class,'MicroTransaction');
        }

        $this->app->singleton("MicroTransaction", function ($app) {
            return new MicroTransactionManager($app);
        });


    }
}
