<?php

namespace App\Plugins\Http;

use App\Plugins\Http\Responses\IHttpResponse;
use App\Plugins\Http\Responses\Ok;
use Nyholm\Psr7\Response;

class ResponseFactory
{
    /**
     * Creates a new Response
     *
     * @param IHttpResponse $response
     * @param array         $headers
     * @param null          $body
     * @param string        $version
     * @param string|null   $reason
     *
     * @return Response
     */
    public static function create(
        IHttpResponse $response = new Ok(),
        array $headers = [],
        $body = null,
        string $version = '1.1',
        string $reason = null
    ): Response {
        return new Response($response->getCode(), $headers, $body ?? $response->getText(), $version, $reason);
    }

    /**
     * Creates a new Response
     *
     * @param IHttpResponse $response
     * @param array         $headers
     * @param null          $body
     * @param string        $version
     * @param string|null   $reason
     *
     * @return Response
     */
    public static function createJson(
        IHttpResponse $response = new Ok(),
        array $headers = [],
                      $body = null,
        string $version = '1.1',
        string $reason = null
    ): Response {

        $body = ResponseUtility::getJson($body ?? $response->getText());

        return new Response($response->getCode(), $headers, $body, $version, $reason);
    }
}