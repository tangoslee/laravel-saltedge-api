<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 07.12.2018
 * Time: 00:25
 */

namespace TendoPay\Integration\SaltEdge\Api;

use GuzzleHttp\Client;

class EndpointCaller
{
    private $client;

    private $apiUrl;

    private $appId;
    private $secret;

    public function __construct()
    {
        $this->client = new Client();
        $this->appId = config("saltedge.app_id");
        $this->secret = config("saltedge.secret");
        $this->apiUrl = config("saltedge.url");
    }

    /**
     *
     *
     * @param $method
     * @param $url
     * @param $payload
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
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

        return $this->client->request($method, $this->apiUrl . $url, $options);
    }
}