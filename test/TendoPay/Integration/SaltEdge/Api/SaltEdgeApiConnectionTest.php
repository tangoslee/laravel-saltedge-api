<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 07.12.2018
 * Time: 00:37
 */

namespace TendoPay\Integration\SaltEdge\Api;

use Orchestra\Testbench\TestCase;
use TendoPay\Integration\SaltEdge\SaltEdgeServiceProvider;

class SaltEdgeApiConnectionTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [SaltEdgeServiceProvider::class];
    }


    public function testShouldCallCustomersListEndpointApi()
    {
        /** @var EndpointCaller $endpointCaller */
        $endpointCaller = $this->app->get(EndpointCaller::class);

        /** @var \GuzzleHttp\Psr7\Response $response */
        $response = $endpointCaller->call("GET", "customers");

        $this->assertEquals(200, $response->getStatusCode());
    }
}