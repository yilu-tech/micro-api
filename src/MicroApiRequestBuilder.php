<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/20
 * Time: 12:58 PM
 */

namespace YiluTech\MicroApi;


use GuzzleHttp\Exception\RequestException;
use YiluTech\MicroApi\Adapters\MicroApiHttpRequest;
use YiluTech\MicroApi\Adapters\MicroApiQueueRequest;
use YiluTech\MicroApi\Adapters\MicroApiRequest;
use YiluTech\MicroApi\Adapters\MicroApiTccRequest;
use YiluTech\MicroApi\Exceptions\MicroApiRequestException;
use GuzzleHttp\HandlerStack;

class MicroApiRequestBuilder
{

    private $gateway;
    private $url;
    private $method;
    private $headers = [];
    private $options = [];


    public function __construct(MicroApiGateway $microApiGateway)
    {
        $this->gateway = $microApiGateway;
    }


    public function get(String $path): MicroApiRequestBuilder
    {
        $this->method = 'GET';
        $this->build($path);
        return $this;
    }

    public function post(String $path): MicroApiRequestBuilder
    {
        $this->method = 'POST';
        $this->build($path);
        return $this;
    }

    public function put(String $path): MicroApiRequestBuilder
    {
        $this->method = 'PUT';
        $this->build($path);
        return $this;
    }

    public function patch(String $path): MicroApiRequestBuilder
    {
        $this->method = 'PATCH';
        $this->build($path);
        return $this;
    }

    public function delete(String $path): MicroApiRequestBuilder
    {
        $this->method = 'DELETE';
        $this->build($path);
        return $this;
    }

    public function query(array $query): MicroApiRequestBuilder
    {
        $this->options['query'] = $query;
        return $this;
    }

    public function json(array $data): MicroApiRequestBuilder
    {
        $this->options['json'] = $data;
        return $this;
    }

    public function form_params(array $data): MicroApiRequestBuilder
    {
        $this->options['form_params'] = $data;
        return $this;
    }


    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function getHeaders(): array
    {
        $default = ["REAL-CLIENT-IP" => $this->getRealIp()];
        return array_merge($default, $this->getGateway()->getHeaders(), $this->headers);
    }

    public function getGateway(): MicroApiGateway
    {
        return $this->gateway;
    }

    public function getManager()
    {
        return $this->gateway->getManager();
    }

    public function getOptions()
    {
        return $this->options;
    }


    public function run()
    {
        $options = [
            'headers' => $this->getHeaders()
        ];

        if ($this->gateway->getManager()->mocker) {
            $handlerStack = HandlerStack::create($this->gateway->getManager()->mocker);
            $options['handler'] = $handlerStack;
        }

        try {
            //??????????????????
            $this->client = new \GuzzleHttp\Client($options);

            $response = $this->client->request($this->getMethod(), $this->getUrl(), $this->getOptions());

            $this->response = new MicroApiResponse($response);
        } catch (RequestException $e) {
            $url = $this->getUrl();
            if ($e instanceof ConnectException) {
                $msg = "MicroApi can not connect: $url";
            } elseif ($e instanceof RequestException && $e->getCode() == 0) {
                $msg = "MicroApi cURL error url malformed: $url";
            } else {
                $msg = $e->getMessage();
            }

            throw new MicroApiRequestException($msg, $e);
        }

        return $this->response;
    }


    private function makeUrl($path)
    {
        //?????????????????????url??????????????????
        if ((stripos($path, 'http://') === false && stripos($path, 'https://') === false)) {
            $this->url = rtrim($this->gateway->getUrl(), '/') . '/' . $path;
        } else {
            $this->url = $path;
        }

        //????????????url????????????????????????????????????
        if (stripos($this->url, 'http://') === false && stripos($this->url, 'https://') === false) {
            throw new MicroApiRequestException("MicroApi Protocol not defined for $this->url.");
        }

        return $this->url;
    }


    private function build(String $path)
    {
        $this->makeUrl($path);
    }

    function getContext()
    {
        $request = get_object_vars($this);
        unset($request['gatewayConfig']);
        return $request;
    }

    public function getRealIp()
    {
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
}
