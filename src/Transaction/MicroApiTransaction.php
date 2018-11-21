<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/20
 * Time: 6:47 PM
 */

namespace YiluTech\MicroApi\Transaction;


use YiluTech\MicroApi\Adapters\MicroApiTransactionRequest;

class MicroApiTransaction
{
    private $coordinater;
    private $items = [];

    public function __construct(MicroApiCoordinator $coordinator)
    {
        $this->coordinater = $coordinator;
    }

    public function begin(){
        //1. 本地数据库记录事物
        $this->createLocalTransactionRecord();
        //2. 向协调器注册事物
        $this->createRemoteTransactionRecrod();
    }

    private function createLocalTransactionRecord(){
        \Log::debug('Coordinator ['.$this->getCoordinater()->getName().'] -向本地数据库记录事物');
    }

    private function createRemoteTransactionRecrod(){
        \Log::debug('Coordinator ['.$this->getCoordinater()->getName().'] -向协调器注册事物');
        $this->getCoordinater()->send('transaction','123123');
    }

    public function getCoordinater(){
        return $this->coordinater;
    }

    public function addItem(MicroApiTransactionRequest $request){
        array_push($this->items,$request->getContext());
        \Log::debug('Coordinator ['.$this->getCoordinater()->getName(). '] -协调器事物添加一个子任务' . $request->getBuiler()->getUrl());
    }

}