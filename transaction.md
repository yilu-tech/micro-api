##最终一致性


    try{
        DB:begin()
        Api::post('send/message')->json(['total'=>"10"])->queue();  //最终一致
        Api::post('order/pay')->json('total'=>'10')->try();         //TCC
        Api::post('inventory/change')->json('amount'=>'1')->try();

        DB:commit();
        Api::confirm();
    }
    catch(Exception $e){
        DB::rollback()
        Api::cancle()
    }


    