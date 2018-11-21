<?php


namespace YiluTech\MicroApi;



use YiluTech\MicroApi\Exceptions\MicroApiException;
use YiluTech\MicroApi\Transaction\MicroApiCoordinator;
use YiluTech\MicroApi\Transaction\MicroApiTransaction;

/**
 * @mixin \YiluTech\MicroApi\MicroApiRequestBuilder
 */
class MicroApiManager
{
    /**
     * The application instance.
     * @var \Illuminate\Foundation\Application
     */
    protected $app;


    private $gateways = [];
    private $requests = [];
    private $queueRequests = [];
    private $tccRequests = [];
    private $coordinators = [];
    /**
     * 用于全局锁定事物
     * @var \YiluTech\MicroApi\MicroApiTransaction
     */
    private $transaction;

    /**
     * 创建MicroApi实例
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function lock(MicroApiTransaction $transaction){
        $this->transaction = $transaction;
    }
    public function unlock(){
        $this->transaction = null;
    }
    public function isLock(){
        return $this->transaction ? true : false;
    }
    public function getCurrentTransaction()
    {
        return $this->transaction;
    }


    /**
     * 获取请求构造器
     * @param string $name
     * @return MicroApiRequestBuilder
     */
    public function gateway($name = 'default'):MicroApiRequestBuilder
    {

        $gatewayBuilder = $this->getGateway($name)->makeBuilder();


        //如果默认调度器已经初始化事物，把默认事物信息加入网关对象
        if($this->coordinator()->hasTransaction()){
            $gatewayBuilder->setTransaction($this->coordinator()->getTransaction());
        }

        return $gatewayBuilder;
    }

    public function getGateway($name):MicroApiGateway
    {
        if(!isset($this->gateways[$name])){
            $this->gateways[$name] = new MicroApiGateway($this,$this->getGatewayConfig($name));
        }
        return $this->gateways[$name];

    }

    /**
     * 获取网关配置
     * @param $name
     * @return array
     */
    private function getGatewayConfig($name):array
    {

        $config =  $this->app['config']["micro.gateways.$name"];
        if(!$config){
            throw new \InvalidArgumentException("MicroApi gateway [{$name}] not configured.");
        }
        return $config;
    }


    /**
     * 获取事物调度器
     * @param string $name
     * @return MicroApiCoordinator
     */
    public function coordinator($name = 'default'): MicroApiCoordinator
    {


        if(!isset($this->coordinators[$name])){
            $this->coordinators[$name] = new MicroApiCoordinator($this,$name,$this->getCoordinatorConfig($name));
        }
        return $this->coordinators[$name];

    }

    /**
     * 获取事物调度器配置
     * @param $name
     * @return array
     */
    private function getCoordinatorConfig($name) : array {
        $config = $this->app['config']["micro.coordinators.$name"];
        if(!$config){
            throw new \InvalidArgumentException("MicroApi coordinator [{$name}] not configured.");
        }
        return $config;
    }

    /**
     * 构造默认事物调度器
     * 并开启默认事物
     * @return mixed
     */
    public function beginTransaction()
    {
        return $this->coordinator()->beginTransaction();
    }


    /**
     * Dynamically pass methods to the default connection.
     * 通过动态方法构造默认Gateway对应的RequestBuilder
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return MicroApiRequestBuilder
     */
    public function __call($method, $parameters):MicroApiRequestBuilder
    {
        //HTTP请求构造器
        $gatewayBuilder =  $this->gateway()->$method(...$parameters);
        return $gatewayBuilder;

    }

}