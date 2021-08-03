<?php

namespace YiluTech\MicroApi;


class MicroApiGateway
{
    private $manager;
    private $url;
    private $headers;

    public function __construct(MicroApiManager $manager,$config)
    {
        $this->manager = $manager;
        $this->headers = $config['headers'] ? $config['headers'] : [];
        $this->url= $config['url'];
    }

    public function getUrl(){
        return $this->url;
    }

    public function getHeaders(){
        return $this->headers;
    }

    public function getManager(){
        return $this->manager;
    }
    public function makeBuilder()
    {
        return new MicroApiRequestBuilder($this);
    }
}
