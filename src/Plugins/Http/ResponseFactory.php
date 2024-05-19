<?php

namespace App\Plugins\Http;

use App\Plugins\Http\Responses\IHttpResponse;
use App\Plugins\Http\Responses\Ok;
use DI\DependencyException;
use DI\NotFoundException;
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
        array         $headers = [],
                      $body = null,
        string        $version = '1.1',
        string        $reason = null
    ): Response
    {
        return new Response($response->getCode(), $headers, $body ?? $response->getText(), $version, $reason);
    }

    /**
     * Creates a new Response
     *
     * @param IHttpResponse $response
     * @param array         $headers
     * @param array|null    $body
     * @param string        $version
     * @param string|null   $reason
     *
     * @return Response
     */
    public static function createJson(
        IHttpResponse $response = new Ok(),
        array         $headers = [],
        ?array        $body = null,
        string        $version = '1.1',
        string        $reason = null
    ): Response
    {
        $body = ResponseUtility::getJson($body ?? $response->getText());

        return new Response($response->getCode(), $headers, $body, $version, $reason);
    }

    /**
     * @param IHttpResponse $response
     * @param string        $templateName
     * @param array|null    $data
     *
     * @return Response
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function createPage(IHttpResponse $response, string $templateName, ?array $data): Response
    {
        $body = ResponseUtility::renderPage($templateName, $data);

        return self::create(response: $response, body: $body);
    }

    /**
     * Return an error page with predefined http errors
     *
     * @param IHttpResponse $response
     *
     * @return Response The response
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function createErrorPage(IHttpResponse $response = new Ok()): Response
    {
        $body = ResponseUtility::renderErrorPage($response);

        return self::create(response: $response, body: $body);
    }
}