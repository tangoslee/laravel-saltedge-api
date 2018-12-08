<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.12.2018
 * Time: 23:40
 */

namespace TendoPay\Integration\SaltEdge;


use TendoPay\Integration\SaltEdge\Api\Accounts\AccountsListFilter;
use TendoPay\Integration\SaltEdge\Api\Accounts\InvalidLoginIdException;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class AccountService
{
    const ACCOUNTS_LIST_API_URL = "accounts";

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


    /**
     * @param AccountsListFilter $accountsListFilter
     * @return mixed
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws InvalidLoginIdException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getList(AccountsListFilter $accountsListFilter)
    {
        try {
            $received = $this->endpointCaller->call("GET", self::ACCOUNTS_LIST_API_URL, $accountsListFilter->toArray());
            return $received->data;
        } catch (ApiEndpointErrorException $exception) {
            switch ($exception->getOriginalError()->error_class) {
                case "LoginNotFound":
                    throw new InvalidLoginIdException();
                default:
                    throw $exception;
            }
        }
    }
}