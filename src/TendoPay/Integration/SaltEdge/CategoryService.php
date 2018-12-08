<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.12.2018
 * Time: 23:41
 */

namespace TendoPay\Integration\SaltEdge;


use TendoPay\Integration\SaltEdge\Api\EndpointCaller;
use TendoPay\Integration\SaltEdge\Api\SaltEdgeApiException;

class CategoryService
{
    const LIST_URL = "categories";

    private $endpointCaller;

    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws SaltEdgeApiException
     */
    public function getAll()
    {
        $responseContent = $this->endpointCaller->call("GET", self::LIST_URL);
        return $responseContent->data;
    }
}