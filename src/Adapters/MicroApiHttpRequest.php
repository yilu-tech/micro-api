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

    public function run(){
        try {

            $this->client = new \GuzzleHttp\Client([
                'headers' => $this->builer->getGateway()->getHeaders()
            ]);

            $response = $this->client->request($this->builer->getMethod(), $this->builer->getUrl());

            $this->response = new MicroApiResponse($response);
        } catch (RequestException $e) {
            throw new MicroApiRequestException($e, $this);
        }

        return $this->response;
    }

}