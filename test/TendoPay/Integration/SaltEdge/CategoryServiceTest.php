<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 08.12.2018
 * Time: 01:02
 */

namespace TendoPay\Integration\SaltEdge;


use Orchestra\Testbench\TestCase;

class CategoryServiceTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [SaltEdgeServiceProvider::class];
    }

    /**
     * @throws Api\SaltEdgeApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldListAllCategories()
    {
        /** @var CategoryService $service */
        $service = $this->app->get(CategoryService::class);
        $categories = $service->getAll();

        $this->assertTrue(is_array($categories) || is_object($categories));
        $this->assertNotEmpty($categories);
    }
}