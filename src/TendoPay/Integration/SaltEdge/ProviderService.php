<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.10.2018
 * Time: 23:39
 */

namespace TendoPay\Integration\SaltEdge;

use GuzzleHttp\Exception\GuzzleException;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;
use TendoPay\Integration\SaltEdge\Api\FilterDateOutOfRangeException;
use TendoPay\Integration\SaltEdge\Api\FilterValueOutOfRangeException;
use TendoPay\Integration\SaltEdge\Api\InvalidProviderCodeException;
use TendoPay\Integration\SaltEdge\Api\Providers\ProvidersListFilter;

class ProviderService
{
    const PROVIDER_SHOW_ENDPOINT_URL = "providers/%s";
    const PROVIDERS_LIST_ENDPOINT_URL = "providers";

    private $endpointCaller;

    /**
     * ProviderService constructor.
     * @param EndpointCaller $endpointCaller
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }


    /**
     * @param $code
     *
     * @return array|\stdClass
     *
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\WrongApiKeyException
     * @throws GuzzleException
     * @throws ApiEndpointErrorException
     * @throws InvalidProviderCodeException
     * @throws Api\UnexpectedStatusCodeException
     */
    public function getByCode($code)
    {
        try {
            $received = $this->endpointCaller->call("GET", sprintf(self::PROVIDER_SHOW_ENDPOINT_URL, $code));
            return $received->data;
        } catch (ApiEndpointErrorException $e) {
            switch ($e->getOriginalError()->error_class) {
                case "ProviderNotFound":
                    throw new InvalidProviderCodeException($e);
                    break;
                default:
                    throw $e;
            }
        }
    }

    /**
     * @param ProvidersListFilter $filters
     *
     * @return mixed
     *
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws FilterDateOutOfRangeException
     * @throws FilterValueOutOfRangeException
     * @throws GuzzleException
     */
    public function getList(ProvidersListFilter $filters)
    {
        try {
            $received = $this->endpointCaller->call("GET", self::PROVIDERS_LIST_ENDPOINT_URL, $filters->toArray());
            return $received->data;
        } catch (ApiEndpointErrorException $e) {
            switch ($e->getOriginalError()->error_class) {
                case "DateOutOfRange":
                    throw new FilterDateOutOfRangeException($e);
                    break;
                case "ValueOutOfRange":
                    throw new FilterValueOutOfRangeException($e);
                    break;
                default:
                    throw $e;
            }
        }
    }
}
