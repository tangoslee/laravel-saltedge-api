<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.12.2018
 * Time: 23:40
 */

namespace TendoPay\Integration\SaltEdge;


use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class CustomerService
{
    private $endpointCaller;

    /**
     * CustomerService constructor.
     *
     * @param EndpointCaller $endpointCaller
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }

    public function getById($id)
    {
        return null;
    }

    public function getAll()
    {
        return [];
    }
}