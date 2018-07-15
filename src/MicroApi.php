<?php

namespace YiluTech\MicroApi;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use YiluTech\MicroApi\MicroApiResponse;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Promise;
use YiluTech\MicroApi\MicroApiRequestException;
use YiluTech\MicroApi\MicroLog;

class MicroApi
{
    private $client;
    private $options;
    private $url;
    private $method;
    private $response;

    private $log;


    public function __construct()
    {
        $this->log = new MicroLog();
        $this->options['headers'] = config('micro.headers') ? config('micro.headers') : [];

        $this->client = new \GuzzleHttp\Client($this->options);
    }

    public function log()
    {
        return $this->log;
    }
    public function getUrl(){
        return $this->url;
    }

    private function makeUrl($uri)
    {
        //如果不是完全的url，就拼接网关
        if((stripos($uri,'http://') === false  && stripos($uri,'https://') === false) && config("micro.api_gateway")){
            $this->url = config('micro.api_gateway').$uri;
        }else{
            $this->url = $uri;
        }

        //检查是否url是否有定义完整的请求协议
        if(stripos($this->url,'http://')  === false  && stripos($this->url,'https://')  === false){
            throw new MicroApiRequestException(null,$this);
        }

        return $this->url;
    }


    function query($query){
        $this->options['query'] = $query;
        return $this;
    }
    function json($data){
        $this->options['json'] = $data;
        return $this;
    }
    function form_params($data){
        $this->options['form_params'] = $data;
        return $this;
    }

    function run(){

        try {
            $this->beforeLog($this->url,$this->method,$this->options);

            $response = $this->client->request($this->method, $this->url, $this->options);

            $this->response =  new MicroApiResponse($response);

            $this->afterLog($this->url,$this->method,$this->options);
        } catch (GuzzleRequestException $e) {
            throw new MicroApiRequestException($e, $this);
        }

        return $this->response;
    }


    /**************sync***************/

    function get(String $uri)
    {
        $this->method = 'GET';
        $this->url = $this->makeUrl($uri);
        return $this;
    }

    function post(String $uri)
    {
        $this->method = 'POST';
        $this->url = $this->makeUrl($uri);
        return $this;
    }

    function put(String $uri)
    {
        $this->method = 'PUT';
        $this->url = $this->makeUrl($uri);
        return $this;
    }

    function patch(String $uri)
    {
        $this->method = 'PATCH';
        $this->url = $this->makeUrl($uri);
        return $this;
    }

    function delete(String $uri)
    {
        $this->method = 'DELETE';
        $this->url = $this->makeUrl($uri);
        return $this;
    }

    /*************async*************/

    function getAsync(String $uri, Array $params)
    {
        $promise = $this->client->getAsync($uri);
        return $promise;
    }

    function postAsync(String $uri, Array $params)
    {
        $promise = $this->client->getAsync($uri);
        return $promise;
    }

    function putAsync(String $uri, Array $params)
    {
        $promise = $this->client->getAsync($uri);
        return $promise;
    }

    function deleteAsync(String $uri, Array $params)
    {
        $promise = $this->client->getAsync($uri);
        return $promise;
    }

    //异步执行的暂时不用
    function promiseRun(Array $promises): Array
    {
        $res = Promise\unwrap($promises);

        foreach ($res as $key => $item) {
            $ret[$key] = new MicroApiResponse($item);
        }
        return $ret;
    }

    protected function beforeLog($url,$method,$options){
        $this->startTiem = microtime(true);
        $this->log()->debug('---------------new request-------------------');
        $this->log()->debug("url: $url");
        $this->log()->debug("Method:$method,  请求地址 $url");
        $this->log()->debug('数据 ', $options);

    }
    protected function afterLog($url,$method,$options){
        $this->log()->debug('数据 ', [$this->response->getJson()]);
        $endTime = microtime(true);
        $runTime = ceil(($endTime - $this->startTiem) * 1000);
        $this->log()->debug("--$url---------------------");
        $this->log()->debug("--执行时间:$runTime ms---------------");
        $this->log()->debug("----------------请求结束--------------------");

    }
}