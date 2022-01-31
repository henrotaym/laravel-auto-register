<?php
namespace Henrotaym\LaravelContainerAutoRegister;

use Henrotaym\LaravelPackageVersioning\Services\Versioning\VersionablePackage;

class Package extends VersionablePackage
{
    public static function prefix(): string
    {
        return "laravel_container_auto_register";
    }
}