<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/22
 * Time: 1:30 AM
 */

class MicroApiTccTransaction
{
    /**
     * 预留资源
     * @return mixed
     */
    abstract function try():boolean;

    /**
     * 确认预留资源
     * @return mixed
     */
    abstract function confirm():boolean;

    /**
     * 取消预留资源
     * @return mixed
     */
    abstract function cancel():boolean;

    function setData():array {

    }

    public function run(){

    }


    private function tryAction(){
        //1. 检查是否已经次事物已经进行过预留
        //2. 预留资源
        //3. 本地事物表记录预留事物
    }

    private function confirmAction(){
        //1. 检查是否已经次事物已经进行过确定
        //2. 执行确定操作
        //3. 本地事物表记录事物的确定状态
    }

    private function cancelAction(){
        //1. 检查是否已经次事物已经进行过取消
        //2. 执行取消操作
        //3. 本地事物表记录事物的取消状态
    }

    /**
     * 事物协调器明确获取到 try,confirm,cancel三种操作的结果后，
     * 回调此接口清理本地事物记录
     * @return bool
     */
    private function clearAction():boolean{


    }
}