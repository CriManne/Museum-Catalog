<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ServiceException;
use App\Models\User;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Injection\DIC;
use App\Plugins\Injection\Injectable;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use League\Plates\Engine;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Nyholm\Psr7\Response;
use Throwable;

class BaseController extends Injectable
{
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
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->plates = DIC::getPlatesEngine();

        $level = DIC::getLoggingLevel();

        $this->apiLogger = new Logger("api_log");
        $this->apiLogger->pushHandler(new StreamHandler("./logs/api_log.log", $level));

        $this->pagesLogger = new Logger("pages_log");
        $this->pagesLogger->pushHandler(new StreamHandler("./logs/pages_log.log", $level));
    }

    /**
     * Returns the logged user email
     * @return ?string
     */
    public function getLoggedUserEmail(): ?string
    {
        return $_SESSION[User::SESSION_EMAIL_KEY] ?? null;
    }

    /**
     * Wrapper function used standardize how the system handles exceptions
     *
     * @param callable $callable
     *
     * @return Response
     */
    public function apiHandler(callable $callable): Response
    {
        try {
            return $callable();
        } catch (ServiceException $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__, $this->getLoggedUserEmail()]);

            return ResponseFactory::createJson(
                response: new BadRequest(
                    text: $e->getMessage()
                )
            );
        } catch (Throwable $e) {
            $this->apiLogger->error($e->getMessage(), [__CLASS__, $this->getLoggedUserEmail()]);

            return ResponseFactory::createJson(
                response: new InternalServerError()
            );
        }
    }
}
