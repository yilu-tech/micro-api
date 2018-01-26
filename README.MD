###使用方法1

        <?php
        
        namespace Center\Controllers;
        use Yilu\MicroApi\MicroApi;
        class BrandController extends Controller
        {
            function list(MicroApi $microApi){
                $microApi->get('store/store/create',[]);;
            }
        }
    
###使用方法2

        \MicroApi::get('store/create',[]);
         
      
      
###异常

        public function testGet(){
    
            try{
                $res = \MicroApi::get('/center/brand/list',[]);
                dump($res->data());  //获取数据
            }catch (MicroApiRequestException $e){
                var_dump($e->getMessage()); //错误描述
                var_dump($e->data());    //错误内容
            }
    
            $this->assertTrue(true);
        } 
        
   如果，没有复杂的事物问题，try-catch可以交给框架去做
   
 
 ###参数说明

        \MicroApi::get($uri,$params);
        \MicroApi::post($uri,$data);
        \MicroApi::put($uri,$data);
        \MicroApi::patch($uri,$data);
        \MicroApi::delete($uri,$params);
        
        $uri 
        1. center/brand/list     center作为模块名在config/micro.php中配置基础地址
        2. http://baidu.com      直接请求这个地址 
        
  ###返回
        
        成功:
        {status:1,data:{}}
        
        MicroApiResponse
            MicroApiResponse->data()     获取  data中值  
        
        
        失败: 
        {status:-1,cause;"错误信息",type:'',data:{}}
        
        MicroApiRequestException
            MicroApiRequestException->data()   
            MicroApiRequestException->getMessage()
            MicroApiRequestException->getMessage()
        