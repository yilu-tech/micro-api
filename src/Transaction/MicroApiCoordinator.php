<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/20
 * Time: 6:51 PM
 */

namespace YiluTech\MicroApi\Transaction;


use YiluTech\MicroApi\Exceptions\MicroApiException;
use YiluTech\MicroApi\MicroApiManager;

class MicroApiCoordinator
{
    private $manager;
    private $name;
    private $transaction;
    /**
     * 调度器的网关
     * @var \YiluTech\MicroApi\MicroApiGateway
     */
    private $gateway;
    private $url_prefix;


    public function __construct(MicroApiManager $manager,$name,$config)
    {
        $this->manager = $manager;
        $this->name = $name;
        $this->gateway = $this->manager->getGateway($config['gateway']);
        $this->url_prefix = $config['url_prefix'];


    }

    public function getRequestBuilder(){
        $this->manager->gateway($this->gateway_name);
    }

    public function hasTransaction(){
        return $this->transaction ? true : false;
    }
    public function getTransaction(){
        if(!$this->transaction){
            throw new MicroApiException("[$this->name] coordinator did not start a transaction.");
        }
        return $this->transaction;
    }
    public function getName(){
        return $this->name;
    }

    public function send($action,$data){
       $ret = $this->gateway->makeBuilder()->post('shop/stmq')->run()->getJson();
       \Log::debug('发送信息给协调器['.$this->name.']: ',$ret);
    }

    /**
     * 开启事物
     * @param string $name
     */
    public function beginTransaction() {

        if( $this->hasTransaction()){
            throw new MicroApiException("MicroApi [$this->name] coordinator  transaction already exists.");
        }
        if($this->manager->isLock()){
            $name = $this->manager->getCurrentTransaction()->getCoordinater()->getName();
            throw new MicroApiException("MicroApi [$name] coordinator  transaction already exists.");
        }


        $this->transaction = new MicroApiTransaction($this);
        $this->transaction->begin();
        $this->manager->lock($this->transaction);

    }

    /**
     * 指定事物请求的网关
     * @param $name
     * @return MicroApiRequestBuilder
     */
    public function gateway($name = 'default'){
        $gateway = $this->manager->getGateway($name);
        $builder = $gateway->makeBuilder();
        $builder->setTransaction($this->getTransaction());
        return $builder;
    }

    /**
     * 通过动态方法构造默认Gateway对应的RequestBuilder
     * @param $method
     * @param $parameters
     * @return MicroApiManager
     */
    public function __call($method, $parameters)
    {
        return $this->gateway()->$method(...$parameters);
    }
}