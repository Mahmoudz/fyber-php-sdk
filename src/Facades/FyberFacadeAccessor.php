<?php
namespace mahmoudz\fyberPhpSdk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class FyberFacadeAccessor
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class FyberFacadeAccessor extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mahmoudz.fyber';
    }
}
