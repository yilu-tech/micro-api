<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/18
 * Time: 11:28 PM
 */

namespace YiluTech\MicroApi;


abstract class MicroApiRequest
{
    protected $builer;
    public function __construct(MicroApiRequestBuilder $builder)
    {
        $this->builer = $builder;
    }
    abstract public function  run();

    public function getBuiler(){
        return $this->builer;
    }
}