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

    /**
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws UnexpectedErrorException
     * @throws WrongApiKeyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldCallCustomersListEndpointApi()
    {
        /** @var EndpointCaller $endpointCaller */
        $endpointCaller = $this->app->get(EndpointCaller::class);

        $response = $endpointCaller->call("GET", "customers");

        $this->assertNotEmpty($response);
    }
}