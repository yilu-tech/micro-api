<?php

namespace Yilu\MicroApi;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
class MicroApiRequestException extends \Exception {
    private $guzzleException;
    private $guzzleResponse;
    private $body;
    private $error_data;
    private $statusCode;
    private $micro;

    public function __construct(GuzzleRequestException $e,MicroApi $micro)
    {
        $this->guzzleException = $e;
        $this->micro = $micro;

        $this->guzzleResponse = $e->getResponse();
        $this->micro->log()->debug("guzzle原始错误:".$e->getMessage());
        $this->micro->log()->debug('code',$e->getTrace());
        if(!$e->hasResponse()){ // 如果没有响应
            return parent::__construct($e->getMessage(),$e->getCode());
        }
        $this->statusCode = $this->guzzleResponse->getStatusCode();
        $this->body= $body = json_decode($this->guzzleResponse->getBody(),true);

        /*
         * 401 验证错误 AuthenticationException
         * 400 业务错误 BusinessException
         * */

        if($this->statusCode == 401 || $this->statusCode == 400){
            $msg = $body['cause'];
        }
        else{
            $msg = $e->getMessage();
        }

        if(isset($body['data'])){
            $this->error_data = $body['data'];
        }

        return parent::__construct($msg,$this->statusCode);
    }


    public function data(){
        return $this->error_data;
    }

    public function getResponse()
    {
        return response()->json([
            'status' => -1,
            'cause' => $this->getMessage(),
            'type' => 'micro_api_error@'.$this->body['type'],
            'data' => $this->error_data
        ], $this->statusCode); //直接返回服务方的状态码
    }

}