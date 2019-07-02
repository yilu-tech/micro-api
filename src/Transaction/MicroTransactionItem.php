<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/20
 * Time: 12:58 PM
 */

namespace YiluTech\MicroApi\Transaction;



class MicroTransactionItem
{

    private $transaction;


    public function __construct(MicroTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Dynamically pass methods to the default connection.
     * 通过动态方法构造默认Gateway对应的RequestBuilder
     *
     * @return \YiluTech\MicroApi\Transaction\MicroTransactionItem
     */
    public function data(){

        return $this;
    }

    public function run(){
        \Log::debug("向远程注册事物");
    }


}
