<?php

namespace Yilu\MicroApi;


use GuzzleHttp\Psr7\Response;
class MicroApiResponse{
    protected $res;

    function __construct(Response $res)
    {
        $this->res = $res;
    }

    function data(){
        $body =  \GuzzleHttp\json_decode($this->res->getBody(),true);
        if(isset($body['data'])){ //解决原PHP数据格式
            return $body["data"];
        }else{ //解析java的数据返回
            return $body;
        }
    }

    function get(String $key){

    }




}