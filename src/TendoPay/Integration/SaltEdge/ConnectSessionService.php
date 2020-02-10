<?php


namespace TendoPay\Integration\SaltEdge;


use ConnectSessionOptions;
use TendoPay\Integration\SaltEdge\Api\Accounts\InvalidLoginIdException;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class ConnectSessionService
{
    public const CREATE_CONNECT_SESSIONS_API_URL = 'connect_sessions/create';

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
     * Allows you to create a connect session, which will be used to access Salt Edge Connect for connection creation.
     *
     * @see https://docs.saltedge.com/account_information/v5/#connect_sessions-create
     *
     * @param $customerId
     * @param $consent
     * @param null $attempt
     * @param array $options
     * @return mixed
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws InvalidLoginIdException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @sample response
        {
            "data": {
                "expires_at": "2020-02-04T15:50:36Z",
                "connect_url": "https://www.saltedge.com/connect?token=GENERATED_TOKEN"
            }
        }
     */
    public function create($customerId, $consent, ConnectSessionOptions $options = null)
    {
        if (!$options) {
            $options = ConnectSessionOptions::builder();
        }

        $options
            ->setCustomerId($customerId)
            ->setConsent($consent);

        $received = $this->endpointCaller->call('POST', self::CREATE_CONNECT_SESSIONS_API_URL, $options->toArray());
        return $received->data;
    }
}
