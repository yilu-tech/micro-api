<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/21
 * Time: 4:10 PM
 */

namespace YiluTech\MicroApi\Adapters;


use YiluTech\MicroApi\MicroApiRequest;

abstract class MicroApiTransactionRequest extends MicroApiRequest
{
    abstract function getContext();

}