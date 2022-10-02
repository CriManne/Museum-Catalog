<?php

declare(strict_types=1);

namespace App\Controller;

use App\Util\DIC;
use DI\Container;
use League\Plates\Engine;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class ControllerUtil {

    protected Engine $plates;

    /**
     * Api controllers logger
     * @var Logger
     */
    protected Logger $api_log;

    /**
     * Pages controllers logger
     * @var Logger
     */
    protected Logger $pages_log;

    /**
     * DI container
     * @var Container
     */
    protected Container $container;

    public function __construct(?Engine $plates = null) {
        if ($plates) {
            $this->plates = $plates;
        }
        $this->container = DIC::getContainer();
        $this->api_log = new Logger("api_log");
        $this->api_log->pushHandler(new StreamHandler("./logs/api_log.log", Level::Info));

        $this->pages_log = new Logger("pages_log");
        $this->pages_log->pushHandler(new StreamHandler("./logs/pages_log.log", Level::Info));
    }

    /**
     * Return a json response
     * @param string $message
     * @param int $status
     * @return string The response json
     */
    public function getResponse(string $message, int $status = 200): string {
        return json_encode(["status" => $status, "message" => $message]);
    }

    /**
     * Return an error page
     * @param string $error_message
     * @param int $error_code
     * @return string The page html as string
     */
    public function displayError(string $error_message, int $error_code): string {
        return $this->plates->render('error::error', ['error_code' => $error_code, 'error_message' => $error_message]);
    }
}
