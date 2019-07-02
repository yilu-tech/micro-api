<?php
namespace YiluTech\MicroApi\Transaction;
use Illuminate\Support\Facades\Facade;


/**
 * @method static \YiluTech\MicroApi\Transaction\MicroTransactionManager  coordinator(string $name)
 * @method static \YiluTech\MicroApi\Transaction\MicroTransaction  begin()
 * @method static \YiluTech\MicroApi\Transaction\MicroTransaction  commit()
 * @method static \YiluTech\MicroApi\Transaction\MicroTransaction  rollback()
 * @method static \YiluTech\MicroApi\Transaction\MicroTransactionItem  delay($uri)
 * @method static \YiluTech\MicroApi\Transaction\MicroTransactionItem  try($uri)
 *
 *
 */
class MicroTransactionFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return 'MicroTransaction';
    }
}
