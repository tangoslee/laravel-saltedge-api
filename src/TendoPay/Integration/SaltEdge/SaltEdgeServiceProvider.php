<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.10.2018
 * Time: 21:34
 */

namespace TendoPay\Integration\SaltEdge;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

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
            /** @var \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application $app */
            return new CategoryService($app->get(EndpointCaller::class));
        });

        $this->app->singleton(CustomerService::class, function ($app) {
            return new CustomerService();
        });

        $this->app->singleton(ProviderService::class, function ($app) {
            return new ProviderService();
        });

        $this->app->singleton(EndpointCaller::class, function ($app) {
            return new EndpointCaller(
                new Client(),
                config("saltedge.url"),
                config("saltedge.app_id"),
                config("saltedge.secret")
            );
        });
    }
}
