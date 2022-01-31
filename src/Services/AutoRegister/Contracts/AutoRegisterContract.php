<?php
namespace Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts;

use Illuminate\Support\Collection;

/** Registering classes. */
interface AutoRegisterContract
{
    /**
     * Scanning and registering given folder.
     * 
     * @param string $path Path to scan.
     * @param string $namespace Default namespace for path.
     * 
     * @return Collection|null
     */
    public function scan(string $path, string $namespace): ?Collection;

    /**
     * Scanning and registering classes in folder where given class is defined.
     * 
     * @param string $class.
     * 
     * @return Collection|null
     */
    public function scanWhere(string $class): ?Collection;

    /**
     * Adding this class to those we should register.
     * 
     * @param string $class.
     * 
     * @return Collection|null
     */
    public function add(string $class): ?Collection;
}