<?php
namespace Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister;

use Illuminate\Support\Collection;
use Henrotaym\LaravelHelpers\Facades\Helpers;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts\AutoRegistrableContract;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts\ClassToRegisterContract;

/** Representing a class that should be registered. */
class ClassToRegister implements ClassToRegisterContract
{
    /**
     * Query name.
     * 
     * @var string
     */
    protected $name;

    /**
     * Query class.
     * 
     * @var string
     */
    protected $class;

    /**
     * @var string|null
     */
    protected $registrable_interface;

    /**
     * Interfaces implemented by class.
     * @var Collection
     */
    protected $interfaces;

    /**
     * Setting file name.
     *  
     * @param string $name
     * @return ClassToRegisterContract
     */
    public function setName(string $name): ClassToRegisterContract
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Setting file name from file with extension.
     *  
     * @param string $name
     * @return ClassToRegisterContract
     */
    public function setNameWithExtension(string $name): ClassToRegisterContract
    {
        return $this->setName(substr($name, 0, strpos($name, '.')));
    }

    /**
     * Setting class name.
     *  
     * @param string $class
     * @return ClassToRegisterContract
     */
    public function setClass(string $class): ClassToRegisterContract
    {
        $this->class = $class;
        
        return $this->setInterfaces();
    }

    protected function setInterfaces(): self
    {
        $interfaces = class_exists($this->class)
            ? class_implements($this->class)
            : null;

        $this->interfaces = collect($interfaces)->values();

        return $this->setRegistrableInterface();
    }

    protected function setRegistrableInterface(): self
    {
        if (!$this->interfaces->contains(AutoRegistrableContract::class)):
            $this->registrable_interface = null;

            return $this;
        endif;

        $this->registrable_interface = $this->interfaces->first(function(string $interface) {
            return Helpers::str_contains($interface, $this->name . "Contract");
        });

        return $this;
    }

    /**
     * Registering query.
     * 
     * @return bool
     */
    public function register(): bool
    {
        if(!$this->isRegistrable()):
            return false;
        endif;

        app()->bind($this->registrable_interface, $this->class);
        
        return true;
    }

    /**
     * Telling if this query can be registered.
     * 
     * @return bool
     */
    public function isRegistrable(): bool
    {
        return !!$this->registrable_interface;
    }
}