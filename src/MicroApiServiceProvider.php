<?php

namespace Yilu\MicroApi;

use Illuminate\Support\ServiceProvider;
use Yilu\MicroApi\MicroApi;

class MicroApiServiceProvider extends ServiceProvider
{
    /**
     * 在容器中注册绑定。
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("MicroApi", function ($app) {
            return new MicroApi();
        });
    }
}