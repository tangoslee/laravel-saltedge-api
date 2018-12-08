<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 08.12.2018
 * Time: 00:46
 */

namespace TendoPay\Integration\SaltEdge\Api;


use stdClass;
use Throwable;

class ApiEndpointErrorException extends SaltEdgeApiException
{
    private $originalError;

    public function __construct(
        stdClass $originalError,
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->originalError = $originalError;
    }

    /**
     * @return stdClass
     */
    public function getOriginalError()
    {
        return $this->originalError;
    }
}