<?php

namespace mahmoudz\fyberPhpSdk;

use Illuminate\Support\ServiceProvider;
use mahmoudz\fyberPhpSdk\Contracts\FyberInterface;
use mahmoudz\fyberPhpSdk\Facades\FyberFacadeAccessor;

/**
 * Class FyberPhpSdkServiceProvider
 *
 * @category Service Provider
 * @package  mahmoudz\fyberPhpSdk
 * @author   Mahmoud Zalt <mahmoud@zalt.me>
 */
class FyberPhpSdkServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the package.
     */
    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | Publish the Config file from the Package to the App directory
        |--------------------------------------------------------------------------
        */
        $this->configPublisher();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Implementation Bindings
        |--------------------------------------------------------------------------
        */
        $this->implementationBindings();

        /*
        |--------------------------------------------------------------------------
        | Facade Bindings
        |--------------------------------------------------------------------------
        */
        $this->facadeBindings();

        /*
        |--------------------------------------------------------------------------
        | Registering Service Providers
        |--------------------------------------------------------------------------
        */
        $this->serviceProviders();
    }

    /**
     * Implementation Bindings
     */
    private function implementationBindings()
    {
        $this->app->bind(
            FyberInterface::class,
            Fyber::class
        );
    }

    /**
     * Publish the Config file from the Package to the App directory
     */
    private function configPublisher()
    {
        $this->publishes([
            __DIR__ . '/Config/fyber-sdk.php' => config_path('fyber-sdk.php'),
        ]);
    }

    /**
     * Facades Binding
     */
    private function facadeBindings()
    {
        $this->app['mahmoudz.fyber'] = $this->app->share(function ($app) {
            return $app->make(Fyber::class);
        });

        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Fyber', FyberFacadeAccessor::class);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Registering Other Custom Service Providers (if you have)
     */
    private function serviceProviders()
    {
        // $this->app->register('...\...\...');
    }

}
