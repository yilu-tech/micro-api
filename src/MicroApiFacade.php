<?php
namespace YiluTech\MicroApi;
use Illuminate\Support\Facades\Facade;


/**
 * @method static \YiluTech\MicroApi\MicroApiRequestBuilder  get(string $url)
 * @method static \YiluTech\MicroApi\MicroApiCoordinator  beginTransaction()
 *
 * @see \Illuminate\Cache\CacheManager
 */
class MicroApiFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return 'MicroApi';
    }
}