<?php
namespace Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts;

/**
 * Representing a class that should be registered.
 */
interface ClassToRegisterContract
{
    /**
     * Setting file name.
     *  
     * @param string $name
     * @return ClassToRegisterContract
     */
    public function setName(string $name): ClassToRegisterContract;

    /**
     * Setting file name from file with extension.
     *  
     * @param string $name
     * @return ClassToRegisterContract
     */
    public function setNameWithExtension(string $name): ClassToRegisterContract;

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