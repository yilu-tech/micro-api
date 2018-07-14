<?php
namespace YiluTech\MicroApi;
use Illuminate\Support\Facades\Facade;

class MicroApiFacade extends Facade
{
    /**
     * 获取组件的注册名称。
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'MicroApi'; }
}