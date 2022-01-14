<?php
namespace Henrotaym\LaravelContainerAutoRegister\Tests\Unit;

use Illuminate\Support\Collection;
use Henrotaym\LaravelContainerAutoRegister\Tests\TestCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use Henrotaym\LaravelContainerAutoRegister\Services\Queries\AutoRegister;
use Henrotaym\LaravelContainerAutoRegister\Testing\Contracts\AppQueryContract;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\QueryAutoRegistrable;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts\AutoRegisterContract;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\Contracts\QueryAutoRegistrableContract;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\Nested\NestedAgain\QueryAutoRegistrable as NestedQueryAutoRegistrable;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\Contracts\QueryNotAutoRegistrableContract;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\Contracts\Nested\NestedAgain\QueryAutoRegistrableContract as NestedQueryAutoRegistrableContract;

class AutoRegisterTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();
        $path = __DIR__ . '/AutoRegister';
        $namespace = "Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister";
        
        app(AutoRegisterContract::class)->scan($path, $namespace);
    }

    /** @test */
    public function auto_register_not_registering_not_auto_registrable()
    {
        $this->expectException(BindingResolutionException::class);   
        app()->make(QueryNotAutoRegistrableContract::class);
    }

    /** @test */
    public function auto_register_registering_auto_registrable()
    {
        $this->assertInstanceOf(QueryAutoRegistrable::class, app()->make(QueryAutoRegistrableContract::class));
    }

    /** @test */
    public function auto_register_registering_nested_auto_registrable()
    {
        $this->assertInstanceOf(NestedQueryAutoRegistrable::class, app()->make(NestedQueryAutoRegistrableContract::class));
    }
}