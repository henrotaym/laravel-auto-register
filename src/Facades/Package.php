<?php
namespace Henrotaym\LaravelContainerAutoRegister\Facades;

use Illuminate\Support\Facades\Facade;
use Henrotaym\LaravelContainerAutoRegister\Package as UnderlyingPackage;

class Package extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return UnderlyingPackage::$prefix;
    }
}