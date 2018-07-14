<?php

namespace Yilu\MicroApi;


use GuzzleHttp\Psr7\Response;

class MicroApiResponse extends Response
{
    private $res;
    private $contents;

    function __construct(Response $res)
    {
        $this->contents = $res->getBody()->getContents();
        parent::__construct($res->getStatusCode(),$res->getHeaders(),$res->getBody());
    }
    
    public function getContents(){
        return $this->contents;
    }

    public function getJson(){
        $data = json_decode($this->contents, true);
        return $data;
    }

}