<?php

declare(strict_types=1);

namespace App\Controller;

use DI\Container;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class ControllerUtil {

    protected Engine $plates;

    /**
     * Api controllers logger
     */
    protected Logger $api_log;

    /**
     * Pages controllers logger
     */
    protected Logger $pages_log;

    /**
     * DI container
     */
    protected Container $container;

    public function __construct(ContainerBuilder $builder,?Engine $plates=null) {
        if ($plates) {
            $this->plates = $plates;
        }
        $builder->addDefinitions('config/container.php');
        $this->container = $builder->build();
        $this->api_log = new Logger("api_log");
        $this->api_log->pushHandler(new StreamHandler("./logs/api_log.log",Level::Info));

        $this->pages_log = new Logger("pages_log");
        $this->pages_log->pushHandler(new StreamHandler("./logs/pages_log.log",Level::Info));
    }

    public function getResponse(string $message, int $status = 200): string {
        return json_encode(["status" => $status, "message" => $message]);
    }

    public function displayError(int $error_code, string $error_message): string {
        return $this->plates->render('error::error', ['error_code' => $error_code, 'error_message' => $error_message]);
    }
}
