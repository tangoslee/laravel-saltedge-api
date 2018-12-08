<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.12.2018
 * Time: 23:40
 */

namespace TendoPay\Integration\SaltEdge;


use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\Customers\CustomerNotFoundException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class CustomerService
{
    const SHOW_CUSTOMER_API_URL = "customers/%s";
    const LIST_CUSTOMERS_API_URL = "customers";

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

    /**
     * @param $id
     * @return mixed
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws CustomerNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getById($id)
    {
        try {
            $received = $this->endpointCaller->call("GET", sprintf(self::SHOW_CUSTOMER_API_URL, $id));
            return $received->data;
        } catch (ApiEndpointErrorException $exception) {
            switch ($exception->getOriginalError()->error_class) {
                case "CustomerNotFound":
                    throw new CustomerNotFoundException();
                default:
                    throw $exception;
            }
        }
    }

    /**
     * @return mixed
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAll()
    {
        $received = $this->endpointCaller->call("GET", self::LIST_CUSTOMERS_API_URL);
        return $received->data;
    }
}