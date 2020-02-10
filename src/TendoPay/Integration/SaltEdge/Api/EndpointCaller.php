<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 07.12.2018
 * Time: 00:25
 */

namespace TendoPay\Integration\SaltEdge\Api;

use ConnectionNotFoundException;
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

    /**
     * EndpointCaller constructor.
     *
     * @param ClientInterface $client http client implementation
     * @param string $apiUrl root API url
     * @param string $appId the app-id used to authorize the API call
     * @param string $secret the secret used to authorize the API call
     */
    public function __construct(ClientInterface $client, $apiUrl, $appId, $secret)
    {
        $this->client = $client;
        $this->appId = $appId;
        $this->secret = $secret;
        $this->apiUrl = $apiUrl;
    }

    /**
     * Prepares for the call and actually calls the SaltEdge API.
     *
     * @param string $method type of http method {@see }
     * @param string $url part of the endpoint URL (all the segments except for the root API url).
     * @param array $payload the data to be sent
     *
     * @return stdClass[]|stdClass the response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException declared due to same declaration in lower methods of GuzzleHttp
     *         client. This exception should be properly handled in this function and it should not be rethrown.
     * @throws ApiEndpointErrorException when unexpected error was returned by the API
     * @throws ApiKeyClientMismatchException when the API key used in the request does not belong to a client
     * @throws ClientDisabledException when the client has been disabled. You can find out more about the disabled
     *         status on {@link https://docs.saltedge.com/guides/your_account/#disabled } guides page
     * @throws UnexpectedStatusCodeException when status code was different than declared by API documentation
     *         {@link https://docs.saltedge.com/reference/#errors }
     * @throws WrongApiKeyException when the API key with the provided App-id and Secret does not exist or is
     *         inactive
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
     * Handles errors and throws non-generic exceptions.
     *
     * @param ResponseInterface $response response from the API. It will contain the API error details.
     *
     * @throws ApiKeyClientMismatchException when the API key used in the request does not belong to a client
     * @throws ClientDisabledException when the client has been disabled. You can find out more about the disabled
     *         status on {@link https://docs.saltedge.com/guides/your_account/#disabled } guides page
     * @throws ApiEndpointErrorException when unexpected error was returned by the API
     * @throws WrongApiKeyException when the API key with the provided App-id and Secret does not exist or is
     *         inactive
     */
    private function handleErrors(ResponseInterface $response)
    {
        $originalError = json_decode($response->getBody()->getContents(), false);

        $errorClass = $originalError->error_class ?? $originalError->error->class ?? null;
        $errorMessage = $originalError->error_message ?? $originalError->error->message ?? null;

        switch ($errorClass) {
            case 'ApiKeyNotFound':
                throw new WrongApiKeyException(sprintf('%s: %s. Request: %s', $errorClass,
                    $errorMessage, var_export($originalError->request, true)));
                break;
            case 'ClientDisabled':
                throw new ClientDisabledException(sprintf('%s: %s. Request: %s', $errorClass,
                    $errorMessage, var_export($originalError->request, true)));
                break;
            case 'ClientNotFound':
                throw new ApiKeyClientMismatchException(sprintf('%s: %s. Request: %s', $errorClass,
                    $errorMessage, var_export($originalError->request, true)));
                break;
            case 'ConnectionNotFound':
                throw new ConnectionNotFoundException(sprintf('%s: %s. Request: %s', $errorClass,
                    $errorMessage, var_export($originalError->request, true)));
                break;
            default:
                throw new ApiEndpointErrorException(
                    $originalError,
                    sprintf('Got error code: %s, error class: %s: %s. Not sure how to handle it. Body of error response: %s',
                        $response->getStatusCode(), $errorClass, $errorMessage, var_export($originalError->request, true))
                );
        }
    }
}
