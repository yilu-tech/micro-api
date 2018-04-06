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
        $this->headers = [
            'Content-Type' => 'application/json',
            'Api-Auth-Type' => 'microservice'
            ];
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

        if (array_key_exists('form_params', $data)) {
            $options = $data;
        } elseif (strtoupper($method) == 'GET' || strtoupper($method) == 'DELETE') {
            $options = ['query' => $data];
        } else {
            $options = ['json' => $data];
        }

        $startTiem = microtime(true);
        $this->log()->debug("#");
        $this->log()->debug("#");
        $this->log()->debug("#");
        $this->log()->debug('---------------新请求-------------------');
        $this->log()->debug("--$url---------------------");
        $this->log()->debug("Method:$method,  请求地址 $url");
        $this->log()->debug('数据 ', $options);
        try {
            $response = $this->client->request($method, $url, $options);
            $this->log()->debug('数据 ', json_decode($response->getBody()->__toString(), 1));
        } catch (GuzzleRequestException $e) {
            throw new MicroApiRequestException($e, $this);
        }
        $endTime = microtime(true);
        $runTime = ceil(($endTime - $startTiem) * 1000);
        $this->log()->debug("--$url---------------------");
        $this->log()->debug("--执行时间:$runTime ms---------------");
        $this->log()->debug("----------------请求结束--------------------");
        return $response;
    }

    /**************sync***************/

    function get(String $uri, Array $params = [])
    {
//        if (count($params)) {
//            $uri .= '?' . http_build_query($params);
//        }
        return new MicroApiResponse($this->runRequest('GET', $uri, $params));
    }

    function post(String $uri, Array $data = [])
    {
        return new MicroApiResponse($this->runRequest('POST', $uri, $data));
    }

    function put(String $uri, Array $data = [])
    {
        return new MicroApiResponse($this->runRequest('PUT', $uri, $data));
    }

    function patch(String $uri, Array $data = [])
    {
        return new MicroApiResponse($this->runRequest('PATCH', $uri, $data));
    }

    function delete(String $uri, Array $params = [])
    {
//        if (count($params)) {
//            $uri .= '?' . http_build_query($params);
//        }
        return new MicroApiResponse($this->runRequest('DELETE', $uri, $params));
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