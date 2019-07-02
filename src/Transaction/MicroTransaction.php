<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/20
 * Time: 6:47 PM
 */

namespace YiluTech\MicroApi\Transaction;


use YiluTech\MicroApi\Adapters\MicroApiTransactionRequest;

class MicroTransaction
{
    private $coordinater;
    private $id;
    private $items = [];

    public function __construct(MicroCoordinator $coordinator)
    {
        $this->coordinater = $coordinator;
    }

    public function begin(){

        //1. 向协调器注册事物
        $this->createRemoteTransactionRecrod();
        //2. 本地数据库记录事物
        $this->createLocalTransactionRecord();
    }

    public function commit(){
        \Log::debug('Coordinator ['.$this->getCoordinater()->getName().'] - commit');
    }

    public function rollback(){
        \Log::debug('Coordinator ['.$this->getCoordinater()->getName().'] - rollback');
    }

    private function createLocalTransactionRecord(){
        \Log::debug('Coordinator ['.$this->getCoordinater()->getName().'] -向本地数据库记录事物');
    }

    private function createRemoteTransactionRecrod(){
        \Log::debug('Coordinator ['.$this->getCoordinater()->getName().'] -向协调器注册事物');
        $data['name'] = 'test';
        $data['sender']['name'] = env("APP_NAME");
        $job = $this->getCoordinater()->send('transactions',$data);
        $this->id = $job['id'];

    }

    public function getCoordinater(){
        return $this->coordinater;
    }

    public function addItem($jobItemType,MicroApiTransactionRequest $request){
        array_push($this->items,$request->getContext());
        $data['id'] = $this->id;
        $data['item']['type'] = $jobItemType;
        $data['item']['url'] = $request->getBuiler()->getUrl();
        $data['item']['data'] = $request->getBuiler()->getOptions()['json'];
        $result = $this->getCoordinater()->send('transactions/jobs',$data);

        \Log::debug('Coordinator ['.$this->getCoordinater()->getName(). '] -协调器事物添加一个子任务' . $request->getBuiler()->getUrl());
    }

    /**
     * 获取事物调度器
     *
     * @param string $name
     * @return \YiluTech\MicroApi\Transaction\MicroTransactionItem
     */
    public function delay($uri):MicroTransactionItem{
        return new MicroTransactionItem($this);
    }

    /**
     * 获取事物调度器
     *
     * @param string $name
     * @return \YiluTech\MicroApi\Transaction\MicroTransactionItem
     */
    public function try($uri):MicroTransactionItem{
        return new MicroTransactionItem($this);
    }


}
