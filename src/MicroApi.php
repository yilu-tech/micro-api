<?php

namespace Yilu\MicroApi;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Yilu\MicroApi\MicroApiResponse;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Promise;
use Yilu\MicroApi\MicroApiRequestException;
use Yilu\MicroApi\MicroLog;

class MicroApi
{
    protected $baseUrl = '';
    private $client;
    private $headers;
    private $options;
    private $log;

    public function __construct()
    {
        $this->log = new MicroLog();
        $this->headers = ['Content-Type' => 'application/json'];
        $this->options['headers'] = $this->headers;
        $this->client = new \GuzzleHttp\Client($this->options);
    }

    public function log()
    {
        return $this->log;
    }

    private function makeUrl($uri)
    {
        $pathArray = explode('/', $uri);
        $module = $pathArray[0];
        $moduleName = "micro.services.$module";
        if (\Config::has($moduleName)) { //模块名称存在，从配置文件读取地址
            $url = config("micro.services.$module") . "/$uri";
        } else {//否则填写的内容直接作为地址
            $url = $uri;
        }

        return $url;
    }

    private function runRequest($method, $uri, $data)
    {
        $url = $this->makeUrl($uri);

        $options = [];

        if (isset($data['form_params'])) {
            $options = array_merge($options, $data);
        } else {
            $options['json'] = $data;
        }

        \MicroApi::log()->debug('----------------------------------------');
        \MicroApi::log()->debug('----------------新请求-------------------');
        \MicroApi::log()->debug('----------------------------------------');
        \MicroApi::log()->debug("Method:$method,  请求地址 $url, 数据 ", $data);
        try {
            $response = $this->client->request($method, $url, $options);
        } catch (GuzzleRequestException $e) {
            throw new MicroApiRequestException($e);
        }
        return $response;
    }

    /**************sync***************/

    function get(String $uri, Array $params = [])
    {
        $res = $this->runRequest('GET', $uri, $params);
        return new MicroApiResponse($res);
    }

    function post(String $uri, Array $data = [], $options = [])
    {
        $res = $this->runRequest('POST', $uri, $data);
        return new MicroApiResponse($res);
    }

    function put(String $uri, Array $data = [])
    {
        $res = $this->runRequest('PUT', $uri, $data);
        return new MicroApiResponse($res);
    }

    function patch(String $uri, Array $data = [])
    {
        $res = $this->runRequest('PATCH', $uri, $data);
        return new MicroApiResponse($res);
    }

    function delete(String $uri, Array $params = [])
    {
        $res = $this->runRequest('DELETE', $uri, $params);
        return new MicroApiResponse($res);
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
}