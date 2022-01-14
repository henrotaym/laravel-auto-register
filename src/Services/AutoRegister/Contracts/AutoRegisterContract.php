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
}