<?php


namespace TendoPay\Integration\SaltEdge\Api;

/**
 * Class ConnectionService
 * @package TendoPay\Integration\SaltEdge\Api
 * @see https://docs.saltedge.com/account_information/v5/#connections-list
 */
class ConnectionService
{
    public const LIST_CONNECTION_API_URL = 'connections';
    public const LIST_CONNECTION_SHOW_API_URL = 'connections/%s';

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
     * Returns all the connections accessible to your application for certain customer
     *
     * @param $customerId
     * @param $fromId
     *
     * @return mixed
     * @throws ApiEndpointErrorException
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws UnexpectedStatusCodeException
     * @throws WrongApiKeyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @sample example
        {
         "data":
            [
                {
                    "country_code": "XF",
                    "created_at": "2020-02-03T14:50:36Z",
                    "customer_id": "905",
                    "daily_refresh": false,
                    "id": "1227",
                    "secret": "AtQX6Q8vRyMrPjUVtW7J_O1n06qYQ25bvUJ8CIC80-8",
                    "show_consent_confirmation": false,
                    "last_consent_id": "102492",
                    "last_attempt": {
                        "api_mode": "service",
                        "api_version": "5",
                        "automatic_fetch": true,
                        "user_present": false,
                        "daily_refresh": false,
                        "categorization": "personal",
                        "created_at": "2020-02-04T14:10:36Z",
                        "customer_last_logged_at": "2020-02-04T11:50:36Z",
                        "custom_fields": {},
                        "device_type": "desktop",
                        "remote_ip": "93.184.216.34",
                        "exclude_accounts": [],
                        "fail_at": null,
                        "fail_error_class": null,
                        "fail_message": null,
                        "fetch_scopes": ["accounts", "transactions"],
                        "finished": true,
                        "finished_recent": true,
                        "from_date": null,
                        "id": "425036",
                        "interactive": false,
                        "locale": "en",
                        "partial": false,
                        "store_credentials": true,
                        "success_at": "2020-02-04T14:10:36Z",
                        "to_date": null,
                        "updated_at": "2020-02-04T14:10:36Z",
                        "show_consent_confirmation": false,
                        "consent_id": "102492",
                        "include_natures": ["account", "card", "bonus"],
                        "last_stage": {
                            "created_at": "2020-02-04T14:10:36Z",
                            "id": "2691802",
                            "interactive_fields_names": null,
                            "interactive_html": null,
                            "name": "finish",
                            "updated_at": "2020-02-04T14:10:36Z"
                        }
                    },
                    "last_success_at": "2020-02-04T14:10:36Z",
                    "next_refresh_possible_at": "2020-02-04T15:50:36Z",
                    "provider_id": "1234",
                    "provider_code": "fakebank_simple_xf",
                    "provider_name": "Fakebank Simple",
                    "status": "active",
                    "store_credentials": true,
                    "updated_at": "2020-02-04T14:10:36Z"
                }
            ],
            "meta" : {
                "next_id": "1228",
                "next_page": "/api/v5/connections?customer_id=100&from_id=1228"
            }
        }
     */
    public function getAll($customerId, $fromId = null)
    {
        $params = [
            'customer_id' => $customerId,
        ];
        $received = $this->endpointCaller->call('GET', self::LIST_CONNECTION_API_URL, $params);
        return $received->data;
    }

    /**
     * Returns a single connection object.
     * @param $customerId
     * @return mixed
     * @throws ApiEndpointErrorException
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws UnexpectedStatusCodeException
     * @throws WrongApiKeyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function show($customerId)
    {
        $url = sprintf(self::LIST_CONNECTION_SHOW_API_URL, $customerId);
        $received = $this->endpointCaller->call('GET', $url);
        return $received->data;
    }
}
