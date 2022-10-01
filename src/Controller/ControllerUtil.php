<?php

declare(strict_types=1);

namespace App\Controller;

use League\Plates\Engine;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class ControllerUtil {

    protected Engine $plates;

    /**
     * Employee area logger
     */
    protected Logger $emp_log;

    /**
     * Public area logger
     */
    protected Logger $pub_log;

    public function __construct(?Engine $plates) {
        if ($plates) {
            $this->plates = $plates;
        }
        $this->emp_log = new Logger("employee_log");
        $this->emp_log->pushHandler(new StreamHandler("./logs/employee_log.log",Level::Info));

        $this->pub_log = new Logger("public_log");
        $this->pub_log->pushHandler(new StreamHandler("./logs/public_log.log",Level::Info));
    }

    public function getResponse(string $message, int $status = 200): string {
        return json_encode(["status" => $status, "message" => $message]);
    }

    public function displayError(int $error_code, string $error_message): string {
        return $this->plates->render('error::error', ['error_code' => $error_code, 'error_message' => $error_message]);
    }
}
