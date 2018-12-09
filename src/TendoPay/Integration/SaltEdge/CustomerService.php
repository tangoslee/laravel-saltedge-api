<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.12.2018
 * Time: 23:40
 */

namespace TendoPay\Integration\SaltEdge;


use stdClass;
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
     * @param EndpointCaller $endpointCaller injected dependency
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }

    /**
     * Fetches customer by id.
     *
     * @link https://docs.saltedge.com/reference/#customers-show
     *
     * @param int $id customer's id
     *
     * @return stdClass the customer
     *
     * @throws Api\ApiEndpointErrorException when unexpected error was returned by the API
     * @throws Api\ApiKeyClientMismatchException when the API key used in the request does not belong to a client
     * @throws Api\ClientDisabledException when the client has been disabled. You can find out more about the disabled
     *         status on {@link https://docs.saltedge.com/guides/your_account/#disabled } guides page
     * @throws Api\UnexpectedStatusCodeException when status code was different than declared by API documentation
     *         {@link https://docs.saltedge.com/reference/#errors }
     * @throws Api\WrongApiKeyException when the API key with the provided App-id and Secret does not exist or is
     *         inactive
     * @throws \GuzzleHttp\Exception\GuzzleException only declared due to lower method's declarations, but should never
     *         be thrown
     * @throws CustomerNotFoundException when customer with given ID could not be found
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
     * Fetches all customers.
     *
     * @link https://docs.saltedge.com/reference/#customers-list
     *
     * @return stdClass[] list of all customers
     *
     * @throws Api\ApiEndpointErrorException when unexpected error was returned by the API
     * @throws Api\ApiKeyClientMismatchException when the API key used in the request does not belong to a client
     * @throws Api\ClientDisabledException when the client has been disabled. You can find out more about the disabled
     *         status on {@link https://docs.saltedge.com/guides/your_account/#disabled } guides page
     * @throws Api\UnexpectedStatusCodeException when status code was different than declared by API documentation
     *         {@link https://docs.saltedge.com/reference/#errors }
     * @throws Api\WrongApiKeyException when the API key with the provided App-id and Secret does not exist or is
     *         inactive
     * @throws \GuzzleHttp\Exception\GuzzleException only declared due to lower method's declarations, but should never
     *         be thrown
     */
    public function getAll()
    {
        $received = $this->endpointCaller->call("GET", self::LIST_CUSTOMERS_API_URL);
        return $received->data;
    }
}