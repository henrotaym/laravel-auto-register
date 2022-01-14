<?php
namespace Henrotaym\LaravelContainerAutoRegister\Providers;

use Illuminate\Support\ServiceProvider;
use Henrotaym\LaravelContainerAutoRegister\Facades\Package;
use Henrotaym\LaravelContainerAutoRegister\Package as UnderlyingPackage;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\AutoRegister;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\ClassToRegister;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts\AutoRegisterContract;
use Henrotaym\LaravelContainerAutoRegister\Services\AutoRegister\Contracts\ClassToRegisterContract;

class LaravelContainerAutoRegisterServiceProvider extends ServiceProvider
{
    /**
     * Registering things to app.
     * 
     * @return void
     */
    public function register()
    {
        $this->bindFacade()
            ->registerConfig()
            ->bindAutoRegisterService();
    }

    /**
     * Binding facade.
     * 
     * @return self
     */
    protected function bindFacade(): self
    {
        $this->app->bind(UnderlyingPackage::$prefix, function($app) {
            return new UnderlyingPackage();
        });

        return $this;
    }
    
    /**
     * Registering config
     * 
     * @return self
     */
    protected function registerConfig(): self
    {
        $this->mergeConfigFrom($this->getConfigPath(), Package::prefix());

        return $this;
    }

    protected function bindAutoRegisterService()
    {
        $this->app->bind(ClassToRegisterContract::class, ClassToRegister::class);
        $this->app->bind(AutoRegisterContract::class, AutoRegister::class);

        return $this;
    }

    /**
     * Booting application.
     * 
     * @return void
     */
    public function boot()
    {
        $this->makeConfigPublishable();
    }

    /**
     * Allowing config publication.
     * 
     * @return self
     */
    protected function makeConfigPublishable(): self
    {
        if ($this->app->runningInConsole()):
            $this->publishes([
              $this->getConfigPath() => config_path(Package::prefix() . '.php'),
            ], 'config');
        endif;

        return $this;
    }

    /**
     * Getting config path.
     * 
     * @return string
     */
    protected function getConfigPath(): string
    {
        return __DIR__.'/../config/config.php';
    }
}