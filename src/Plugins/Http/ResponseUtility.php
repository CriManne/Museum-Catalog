<?php

namespace App\Plugins\Http;

use App\Plugins\Http\Responses\IHttpResponse;
use App\Plugins\Http\Responses\Ok;
use App\Plugins\Injection\DIC;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class ResponseUtility
{
    private const string URL_API_MATCH = '/api/';

    /**
     * Return a json response
     *
     * @param IHttpResponse|string $response
     *
     * @return string The response json
     */
    public static function getJson(IHttpResponse|string $response): string
    {
        if (is_string($response)) {
            return json_encode(["message" => $response]);
        }

        return json_encode(["message" => $response->getText()]);
    }

    /**
     * Determines whether the given request is for the api or not.
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public static function isRequestAPI(ServerRequestInterface $request): bool
    {
        $requestedUrl = $request->getRequestTarget();

        return str_contains($requestedUrl, self::URL_API_MATCH);
    }

    /**
     * Returns the response body by the given predefined http response
     *
     * @param ServerRequestInterface $request
     * @param IHttpResponse          $response
     *
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function getHttpResponseBody(ServerRequestInterface $request, IHttpResponse $response): string
    {
        return self::isRequestAPI($request)
            ? self::getJson($response)
            : self::renderErrorPage($response);
    }

    /**
     * @param string     $templateName
     * @param array|null $data
     *
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function renderPage(string $templateName, ?array $data): string
    {
        return DIC::getPlatesEngine()->render($templateName, $data);
    }

    /**
     * @param IHttpResponse $response
     *
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function renderErrorPage(IHttpResponse $response = new Ok()): string
    {
        return self::renderPage('error::error', ['error_code' => $response->getCode(), 'error_message' => $response->getText()]);
    }
}