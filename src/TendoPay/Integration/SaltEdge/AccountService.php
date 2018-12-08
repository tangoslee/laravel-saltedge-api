<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.12.2018
 * Time: 23:40
 */

namespace TendoPay\Integration\SaltEdge;


use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class AccountService
{
    private $endpointCaller;

    /**
     * AccountService constructor.
     *
     * @param EndpointCaller $endpointCaller
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }


    public function getList($filters = [])
    {
        return [];
    }
}