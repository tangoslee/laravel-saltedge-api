<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 07.12.2018
 * Time: 00:25
 */

namespace TendoPay\Integration\SaltEdge\Api;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class EndpointCaller
{
    private $client;

    private $apiUrl;

    private $appId;
    private $secret;

    public function __construct(ClientInterface $client, $apiUrl, $appId, $secret)
    {
        $this->client = $client;
        $this->appId = $appId;
        $this->secret = $secret;
        $this->apiUrl = $apiUrl;
    }

    /**
     *
     *
     * @param $method
     * @param $url
     * @param $payload
     *
     * @return array|stdClass
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnexpectedErrorException
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws WrongApiKeyException
     */
    public function call($method, $url, array $payload = [])
    {
        $options = [
            "headers" => [
                "Accept" => "application/json",
                "Content-type" => "application/json",
                "App-id" => $this->appId,
                "Secret" => $this->secret,
            ]
        ];

        if (!empty($payload)) {
            $options["json"] = $payload;
        }

        try {
            $response = $this->client->request($method, $this->apiUrl . $url, $options);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
        }

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents());
        } elseif (array_search($response->getStatusCode(), [400, 404, 406, 409]) !== false) {
            $this->handleErrors($response);
            // no return, will throw exception
        } else {
            throw new UnexpectedErrorException(sprintf("Got error code: %s. Not sure how to handle it. Response: %s",
                $response->getStatusCode(), var_export($response->getBody()->getContents(), true)));
        }
    }

    /**
     * @param ResponseInterface $response
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws UnexpectedErrorException
     * @throws WrongApiKeyException
     */
    private function handleErrors(ResponseInterface $response)
    {
        $body = json_decode($response->getBody()->getContents());

        switch ($body->error_class) {
            case "ApiKeyNotFound":
                throw new WrongApiKeyException(sprintf("%s: %s. Request: %s", $body->error_class,
                    $body->error_message, var_export($body->request, true)));
                break;
            case "ClientDisabled":
                throw new ClientDisabledException(sprintf("%s: %s. Request: %s", $body->error_class,
                    $body->error_message, var_export($body->request, true)));
                break;
            case "ClientNotFound":
                throw new ApiKeyClientMismatchException(sprintf("%s: %s. Request: %s", $body->error_class,
                    $body->error_message, var_export($body->request, true)));
                break;
            default:
                throw new UnexpectedErrorException(sprintf("Got error code: %s, error class: %s. Not sure how to handle it. Body of error response: %s",
                    $response->getStatusCode(), $body->error_class, var_export($body, true)));
        }
    }
}