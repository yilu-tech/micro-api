<?php


namespace YiluTech\MicroApi\Transaction;





/**
 * @mixin \YiluTech\MicroApi\Transaction\MicroTransaction
 */
class MicroTransactionManager
{
    /**
     * The application instance.
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    private $coordinators = [];


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








    /**
     * 获取事物调度器
     *
     * @param string $name
     * @return \YiluTech\MicroApi\Transaction\MicroCoordinator
     */
    public function coordinator($name=null)
    {
        if($name == null){
            $name = $this->app['config']["micro.default_coordinator"];
        }

        if(!isset($this->coordinators[$name])){
            $this->coordinators[$name] = new MicroCoordinator($this,$name,$this->getCoordinatorConfig($name));
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


    public function begin(){
        $this->coordinator()->begin();
    }

    /**
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        //HTTP请求构造器
        $gatewayBuilder =  $this->coordinator()->getTransaction()->$method(...$parameters);
        return $gatewayBuilder;

    }

}
