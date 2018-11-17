##最终一致性


    try{
        
        Api::post('order/status')->transaction();
        DB:begin()
        
        Api::post('send/message')->json(['total'=>"10"])->queue();  //最终一致
        Api::post('order/pay')->json('total'=>'10')->try();         //TCC
        Api::gateway('other')->post('inventory/change')->json('amount'=>'1')->try();
        DB::update();

        DB:commit();
        Api::confirm();
    }
    catch(Exception $e){
        DB::rollback()
        Api::cancel()
    }
    
    
    
    
    class MciroApiTcc{
        function try(){
            
        }
        
        function confirm(){
            
        }
        
        function cancel(){
            
        }
        
        function run(){
            
        }
    }

    