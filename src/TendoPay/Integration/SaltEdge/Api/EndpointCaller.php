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
     * @throws ApiEndpointErrorException
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws WrongApiKeyException
     * @throws UnexpectedStatusCodeException
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
            throw new UnexpectedStatusCodeException(sprintf("Got error code: %s. Not sure how to handle it. Response: %s",
                $response->getStatusCode(), var_export($response->getBody()->getContents(), true)));
        }
    }

    /**
     * @param ResponseInterface $response
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws ApiEndpointErrorException
     * @throws WrongApiKeyException
     */
    private function handleErrors(ResponseInterface $response)
    {
        $originalError = json_decode($response->getBody()->getContents());

        switch ($originalError->error_class) {
            case "ApiKeyNotFound":
                throw new WrongApiKeyException(sprintf("%s: %s. Request: %s", $originalError->error_class,
                    $originalError->error_message, var_export($originalError->request, true)));
                break;
            case "ClientDisabled":
                throw new ClientDisabledException(sprintf("%s: %s. Request: %s", $originalError->error_class,
                    $originalError->error_message, var_export($originalError->request, true)));
                break;
            case "ClientNotFound":
                throw new ApiKeyClientMismatchException(sprintf("%s: %s. Request: %s", $originalError->error_class,
                    $originalError->error_message, var_export($originalError->request, true)));
                break;
            default:
                throw new ApiEndpointErrorException(
                    $originalError,
                    sprintf("Got error code: %s, error class: %s. Not sure how to handle it. Body of error response: %s",
                        $response->getStatusCode(), $originalError->error_class, var_export($originalError, true))
                );
        }
    }
}