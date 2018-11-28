<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/19
 * Time: 10:29 PM
 */

namespace YiluTech\MicroApi\Adapters;

use GuzzleHttp\Exception\RequestException;
use YiluTech\MicroApi\Exceptions\MicroApiRequestException;
use YiluTech\MicroApi\MicroApiRequest;
use YiluTech\MicroApi\MicroApiResponse;

class MicroApiHttpRequest extends MicroApiRequest
{

    public function run()
    {
        try {
            //请求基础信息
            $this->client = new \GuzzleHttp\Client([
                'headers' => $this->builer->getGateway()->getHeaders()
            ]);

            $response = $this->client->request($this->builer->getMethod(), $this->builer->getUrl(),$this->builer->getOptions());

            $this->response = new MicroApiResponse($response);
        } catch (RequestException $e) {
            $url = $this->builer->getUrl();
            if($e instanceof ConnectException){
                $msg = "MicroApi can not connect: $url";
            }
            elseif($e instanceof RequestException && $e->getCode() == 0){
                $msg = "MicroApi cURL error url malformed: $url";
            }
            else{
                $msg = $e->getMessage();
            }
            
            throw new MicroApiRequestException($msg,$e);
        }

        return $this->response;
    }

}