<?php
namespace YiluTech\MicroApi;
use Illuminate\Support\Facades\Facade;


/**
 * @method static \YiluTech\MicroApi\MicroApiManager  gateway(string $name)
 * @method static \YiluTech\MicroApi\MicroApiRequestBuilder  get(string $url)
 * @method static \YiluTech\MicroApi\MicroApiRequestBuilder  post(string $url)
 * @method static \YiluTech\MicroApi\MicroApiRequestBuilder  put(string $url)
 * @method static \YiluTech\MicroApi\MicroApiRequestBuilder  delete(string $url)
 *
 * @see \Illuminate\Cache\CacheManager
 */
class MicroApiFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return 'MicroApi';
    }
}
