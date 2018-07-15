<?php

namespace YiluTech\MicroApi;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

class MicroApiRequestException extends GuzzleRequestException {

    public $body;

    public function __construct(GuzzleRequestException $e = null,MicroApi $microApi)
    {
        $url = $microApi->getUrl();

        if($e === null){
            $this->message = "MicroApi Protocol not defined for ${url}.";
            return;
        }
        elseif($e instanceof ConnectException){
            $msg = "MicroApi can not connect: ${url}";
        }
        elseif($e instanceof RequestException && $e->getCode() == 0){
            $msg = "MicroApi cURL error url malformed: ${url}";
        }
        else{
            $msg = $e->getMessage();
        }
        return parent::__construct($msg,$e->getRequest(),$e->getResponse(),$e->getPrevious());
    }



    public function getData(){
        if(!$this->hasResponse()){
            return null;
        }

        $data = json_decode($this->getResponse()->getBody()->__toString(), 1);
        if(json_last_error() == JSON_ERROR_NONE){
            return $data;
        }else{
            return $this->getResponse()->getBody()->__toString();
        }
    }

}