<?php

declare(strict_types=1);

namespace App\Controller;

use App\Plugins\Http\Responses\IHttpResponse;
use App\Plugins\Http\Responses\Ok;
use App\Plugins\Injection\DIC;
use App\Plugins\Injection\Injectable;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use League\Plates\Engine;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;

class BaseController extends Injectable
{
    private const string URL_API_MATCH = '/api/';

    /**
     * Template render engine
     * @var ?Engine
     */
    protected ?Engine $plates;

    /**
     * Api controllers logger
     * @var Logger
     */
    protected Logger $apiLogger;

    /**
     * Pages controllers logger
     * @var Logger
     */
    protected Logger $pagesLogger;

    /**
     * @param Engine|null $plates
     *
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function __construct(?Engine $plates = null)
    {
        parent::__construct();

        $this->plates = $plates;

        $level = DIC::getLoggingLevel();

        $this->apiLogger = new Logger("api_log");
        $this->apiLogger->pushHandler(new StreamHandler("./logs/api_log.log", $level));

        $this->pagesLogger = new Logger("pages_log");
        $this->pagesLogger->pushHandler(new StreamHandler("./logs/pages_log.log", $level));
    }

    /**
     * Return a json response
     *
     * @param IHttpResponse|string $response
     *
     * @return string The response json
     */
    public function getJson(IHttpResponse|string $response): string
    {
        if (is_string($response)) {
            return json_encode(["message" => $response]);
        }

        return json_encode(["message" => $response->getText()]);
    }

    /**
     * Return an error page with predefined http errors
     *
     * @param IHttpResponse $response
     *
     * @return string The page html as string
     */
    public function getErrorPage(IHttpResponse $response = new Ok()): string
    {
        return $this->plates->render('error::error', ['error_code' => $response->getCode(), 'error_message' => $response->getText()]);
    }

    /**
     * Return an error page
     *
     * @param string        $error_message
     * @param IHttpResponse $response
     *
     * @return string The page html as string
     */
    public function getCustomErrorPage(string $error_message, IHttpResponse $response = new Ok()): string
    {
        return $this->plates->render('error::error', ['error_code' => $response->getCode(), 'error_message' => $error_message]);
    }

    /**
     * Determines whether the given request is for the api or not.
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function isRequestAPI(ServerRequestInterface $request): bool
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
     */
    public function getHttpResponseBody(ServerRequestInterface $request, IHttpResponse $response): string
    {
        return $this::isRequestAPI($request)
            ? $this->getJson($response)
            : $this->getErrorPage($response);
    }
}
