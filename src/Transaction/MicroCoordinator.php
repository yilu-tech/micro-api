<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/11/20
 * Time: 6:51 PM
 */

namespace YiluTech\MicroApi\Transaction;


use YiluTech\MicroApi\Exceptions\MicroApiException;
use YiluTech\MicroApi\MicroApiManager;

class MicroCoordinator
{
    private $manager;
    private $name;
    private $transaction;
    /**
     * 调度器的网关
     * @var \YiluTech\MicroApi\MicroApiGateway
     */
    private $host;
    private $port;




    public function __construct(MicroTransactionManager $manager,$name,$config)
    {
        $this->manager = $manager;
        $this->name = $name;
        $this->host = $config['host'];
        $this->port = $config['port'];


    }


    public function hasTransaction(){
        return $this->transaction ? true : false;
    }


    public function getTransaction(){
        if(!$this->transaction){
            throw new MicroApiException("[$this->name] coordinator did not start a transaction.");
        }
        return $this->transaction;
    }

    public function getName(){
        return $this->name;
    }

    public function send($action,$data=[]){
        $url = "http://$this->host:$this->port/$action";

        $data['coordinator'] = $this->name;
    }




    public function begin() {

        if( $this->hasTransaction()){
            throw new MicroApiException("MicroApi [$this->name] coordinator  transaction already exists.");
        }


        $this->transaction = new MicroTransaction($this);
        $this->transaction->begin();
    }

}
