<?php


namespace YiluTech\MicroApi;


use GuzzleHttp\Handler\MockHandler;

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
    public $mocker;

    private $gateways = [];

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
     * 获取请求构造器
     * @param string $name
     * @return MicroApiRequestBuilder
     */
    public function gateway($name = 'default'):MicroApiRequestBuilder
    {

        $gatewayBuilder = $this->getGateway($name)->makeBuilder();
        

        return $gatewayBuilder;
    }

    public function getGateway($name):MicroApiGateway
    {
        if(!isset($this->gateways[$name])){
            $this->gateways[$name] = new MicroApiGateway($this,$this->getGatewayConfig($name));
        }
        return $this->gateways[$name];

    }

    public function mock($mockers)
    {
      $this->mocker = new MockHandler($mockers);
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
