<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.10.2018
 * Time: 21:34
 */

namespace TendoPay\Integration\SaltEdge;

use Illuminate\Support\ServiceProvider;

class SaltEdgeServiceProvider extends ServiceProvider
{
    /**
     * Loads the configuration file.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../../config/saltedge.php', 'saltedge');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AccountService::class, function ($app) {
            return new AccountService();
        });

        $this->app->singleton(CategoryService::class, function ($app) {
            return new CategoryService();
        });

        $this->app->singleton(CustomerService::class, function ($app) {
            return new CustomerService();
        });

        $this->app->singleton(ProviderService::class, function ($app) {
            return new ProviderService();
        });
    }
}
