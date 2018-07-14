<?php

namespace YiluTech\MicroApi;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MicroLog extends Logger{
    public $log;

     function __construct()
     {
         parent::__construct("micro");
         $this->pushHandler(new StreamHandler(storage_path('logs/micro.log')));
     }
}