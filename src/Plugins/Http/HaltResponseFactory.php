<?php

namespace App\Plugins\Http;

use App\Plugins\Http\Responses\IHttpResponse;
use SimpleMVC\Response\HaltResponse;

class HaltResponseFactory
{
    /**
     * Creates a new HaltResponse
     *
     * @param IHttpResponse $response
     * @param array         $headers
     * @param null          $body
     * @param string        $version
     * @param string|null   $reason
     *
     * @return HaltResponse
     */
    public static function create(
        IHttpResponse $response,
        array $headers = [],
        $body = null,
        string $version = '1.1',
        string $reason = null
    ): HaltResponse {
        return new HaltResponse($response->getCode(), $headers, $body, $version, $reason);
    }

    /**
     * Creates a new HaltResponse
     *
     * @param IHttpResponse $response
     * @param array         $headers
     * @param string        $version
     * @param string|null   $reason
     *
     * @return HaltResponse
     */
    public static function createDefaultHttpResponse(
        IHttpResponse $response,
        array $headers = [],
        string $version = '1.1',
        string $reason = null
    ): HaltResponse {
        return new HaltResponse($response->getCode(), $headers, $response->getText(), $version, $reason);
    }
}