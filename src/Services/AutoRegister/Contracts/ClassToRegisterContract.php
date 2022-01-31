<?php
namespace Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts;

/**
 * Representing a class that should be registered.
 */
interface ClassToRegisterContract
{
    /**
     * Setting class name.
     *  
     * @param string $class
     * @return ClassToRegisterContract
     */
    public function setClass(string $class): ClassToRegisterContract;

    /**
     * Registering query.
     * 
     * @return bool
     */
    public function register(): bool;

    /**
     * Telling if this query can be registered.
     * 
     * @return bool
     */
    public function isRegistrable(): bool;
}