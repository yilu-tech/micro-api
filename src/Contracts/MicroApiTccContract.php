<?php

namespace YiluTech\MicroApi\Contracts;
use Illuminate\Http\Request;

abstract class MicroApiTccContract
{
    abstract function try();
    abstract function confirm();
    abstract function cancel();

    private function trySucceed(){

    }

    private function tryFailed(){

    }

    private function confirmSucceed(){

    }

    private function confirmFailed(){

    }

    private function cancelSucceed(){

    }

    private function cancelFailed(){

    }

    public function run(Request $request){


    }

}