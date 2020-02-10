<?php


namespace TendoPay\Integration\SaltEdge;


use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class TransactionService
{
    public const LIST_TRANSACTION_API_URL = 'transactions';

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
     * Fetches list of non duplicated transactions of an account
     *
     * @link https://docs.saltedge.com/account_information/v5/#transactions-list
     *
     * @param $connectionId         the id of the connection
     * @param string $accountId     the id of the account
     * @param string $fromId        the id of the transaction which the list starts with
     *
     * @return mixed
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
     *
     * @sample response
     *
        {
            "data": [
                {
                    "id": "987",
                    "duplicated": false,
                    "mode": "normal",
                    "status": "posted",
                    "made_on": "2013-05-03",
                    "amount": -200.0,
                    "currency_code": "USD",
                    "description": "test transaction",
                    "category": "advertising",
                    "extra": {
                        "original_amount": -3974.60,
                        "original_currency_code": "CZK",
                        "posting_date": "2013-05-07",
                        "time": "23:56:12"
                    },
                    "account_id": "100",
                    "created_at": "2020-02-02T14:50:36Z",
                    "updated_at": "2020-02-03T14:50:36Z"
                },
                {
                    "id": "988",
                    "duplicated": false,
                    "mode": "normal",
                    "status": "posted",
                    "made_on": "2013-05-03",
                    "amount": 50.0,
                    "currency_code": "USD",
                    "description": "business expense",
                    "category": "business_services",
                    "extra": {
                        "original_amount": 993.90,
                        "original_currency_code": "CZK",
                        "posting_date": "2013-05-06",
                        "time": "12:16:25"
                    },
                    "account_id": "100",
                    "created_at": "2020-02-02T14:50:36Z",
                    "updated_at": "2020-02-03T14:50:36Z"
                }
            ],
            "meta" : {
                "next_id": "990",
                "next_page": "/api/v5/transactions/?connection_id=124&account_id=225&from_id=990"
            }
        }
     */
    public function getAll($connectionId, $accountId = '', $fromId = '')
    {
        $params = [
            'connection_id' => $connectionId,
            'account_id' => $accountId,
            'fromId' => $fromId,
        ];
        $received = $this->endpointCaller->call('GET', self::LIST_TRANSACTION_API_URL, $params);
        return $received->data;
    }
}
