<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/18
 * Time: 11:29 PM
 */
namespace YiluTech\MicroApi\Adapters;



use YiluTech\MicroApi\MicroApiRequestBuilder;

class MicroApiQueueRequest extends MicroApiTransactionRequest
{
    private $context;

    /**
     * 执行
     */
    function run(){
        //包装数据
        $this->wrapContext();
        //添加到事物
        $this->getBuiler()->getTransaction()->addItem($this);
        return $this;
    }

    /**
     * 包装
     */
    function wrapContext(){
        $this->context = ['任务信息'];
    }


    function getContext()
    {
        // TODO: Implement getContext() method.
    }

//    function checkout(){
//
//    }
}