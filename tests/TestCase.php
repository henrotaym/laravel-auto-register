<?php
namespace Henrotaym\LaravelContainerAutoRegister\Tests;

use Henrotaym\LaravelContainerAutoRegister\Package;
use Henrotaym\LaravelPackageVersioning\Testing\VersionablePackageTestCase;
use Henrotaym\LaravelContainerAutoRegister\Providers\LaravelContainerAutoRegisterServiceProvider;

class TestCase extends VersionablePackageTestCase
{
    public static function getPackageClass(): string
    {
        return Package::class;
    }
    
    /**
     * Providers used bu application (manual registration is compulsory)
     * 
     * @return array
     */
    public function getServiceProviders(): array
    {
        return [
            LaravelContainerAutoRegisterServiceProvider::class,
        ];
    }
}