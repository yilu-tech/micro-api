<?php

namespace Yilu\MicroApi;


use GuzzleHttp\Psr7\Response;
use GuzzleHttp;

class MicroApiResponse{
    protected $res;

    function __construct(Response $res)
    {
        $this->res = $res;
    }

    function data()
    {
        $data = null;
        $jsonString = $this->res->getBody()->__toString();

        if ($jsonString && !empty($jsonString)) {

            $body = \GuzzleHttp\json_decode($jsonString, true);
            if (isset($body['data'])) { //解决原PHP数据格式
                $data = $body["data"];
            } else { //解析java的数据返回
                $data = $body;
            }
        }

        return $data;
    }

    function get(String $key){

    }




}