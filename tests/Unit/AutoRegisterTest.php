<?php
namespace Henrotaym\LaravelContainerAutoRegister\Tests\Unit;

use Mockery\MockInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Henrotaym\LaravelContainerAutoRegister\Tests\TestCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\AutoRegister;
use Henrotaym\LaravelContainerAutoRegister\Testing\Contracts\AppQueryContract;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\QueryAutoRegistrable;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Exceptions\FolderNotFound;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts\AutoRegisterContract;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\Contracts\QueryAutoRegistrableContract;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\Contracts\QueryNotAutoRegistrableContract;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\Nested\NestedAgain\QueryAutoRegistrable as NestedQueryAutoRegistrable;
use Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister\Contracts\Nested\NestedAgain\QueryAutoRegistrableContract as NestedQueryAutoRegistrableContract;

class AutoRegisterTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();
    }

    /** @test */
    public function auto_register_not_registering_not_auto_registrable()
    {
        $this->scanFolder();
        $this->expectException(BindingResolutionException::class);   
        app()->make(QueryNotAutoRegistrableContract::class);
    }

    /** @test */
    public function auto_register_registering_auto_registrable()
    {
        $this->scanFolder();
        $this->assertInstanceOf(QueryAutoRegistrable::class, app()->make(QueryAutoRegistrableContract::class));
    }

    /** @test */
    public function auto_register_registering_nested_auto_registrable()
    {
        $this->scanFolder();
        $this->assertInstanceOf(NestedQueryAutoRegistrable::class, app()->make(NestedQueryAutoRegistrableContract::class));
    }

    /** @test */
    public function auto_register_reporting_wrong_path()
    {
        $path = __DIR__ . "/testastos";

        Log::shouldReceive('error')
            ->withArgs(function($message) use ($path) {
                return $message === FolderNotFound::path($path)->getMessage();
            });

        $this->scanFolder($path);
    }

    /** @test */
    public function auto_register_scanning_where_returning_null_if_invalid_class()
    {
        $this->mockAutoRegister();
        $undefined_class = "testastos";

        $this->mocked_auto_register->expects()->scanWhere($undefined_class)->passthru();
        $this->mocked_auto_register->expects()->scan()->withAnyArgs()->times(0);

        $this->assertNull($this->mocked_auto_register->scanWhere($undefined_class));
    }

    /** @test */
    public function auto_register_scanning_correctly_with_correct_class()
    {
        $this->mockAutoRegister();
        $correct_class = QueryAutoRegistrable::class;

        $this->mocked_auto_register->expects()->scanWhere($correct_class)->passthru();
        $this->mocked_auto_register->expects()->scan()
            ->with( realpath(__DIR__ .'\AutoRegister'), 'Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister')
            ->andReturn(collect());

        $this->assertNotNull($this->mocked_auto_register->scanWhere($correct_class));
    }

    /** @var MockInterface */
    protected $mocked_auto_register;

    protected function mockAutoRegister()
    {
        $this->mocked_auto_register = $this->mockThis(AutoRegister::class);

        return $this;
    }

    protected function getAutoRegister()
    {
        return app(AutoRegisterContract::class);
    }

    protected function scanFolder(string $path = null)
    {
        $path = $path ?? __DIR__ . '/AutoRegister';
        $namespace = "Henrotaym\LaravelContainerAutoRegister\Tests\Unit\AutoRegister";
        
        $this->getAutoRegister()->scan($path, $namespace);
    }
}