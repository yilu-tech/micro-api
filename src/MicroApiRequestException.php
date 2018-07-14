<?php

namespace YiluTech\MicroApi;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MicroApiRequestException extends HttpException {

    public $body;

    public function __construct(GuzzleRequestException $e = null,MicroApi $microApi)
    {
        $url = $microApi->getUrl();
        $previous = null;
        if($e === null){
            $msg = "MicroApi Protocol not defined for ${url}.";
        }
        elseif($e instanceof ConnectException){
            $msg = "MicroApi can not connect: ${url}";
            $code = $e->getCode();
            $previous = $e->getPrevious();
        }
        elseif($e instanceof RequestException && $e->getCode() == 0){
            $msg = "MicroApi cURL error url malformed: ${url}";
            $code = $e->getCode();
            $previous = $e->getPrevious();
        }
        else{
            $this->body = $this->parseBody($e);
            $msg = $e->getMessage();
        }
        
        return parent::__construct(502,$msg,$previous);
    }

    protected function parseBody($e){
        $body = $e->getResponse()->getBody()->__toString();
        if($this->isJSON($body)){
            $body = json_decode($e->getResponse()->getBody()->__toString(), 1);
        }
        return $body;
    }

    public function getBody(){
        return $this->body;
    }

    protected  function isJSON($string){
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}