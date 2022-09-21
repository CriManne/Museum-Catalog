<?php

declare(strict_types=1);

namespace App\Controller;

use League\Plates\Engine;

class ControllerUtil {

    protected Engine $plates;

    public function __construct(?Engine $plates) {
        if($plates){
            $this->plates = $plates;
        }
    }

    public function getResponse(string $message,int $status=200):string{
        return json_encode(["status"=>$status,"message"=>$message]);
    }

    public function displayError(int $error_code, string $error_message): string {
        return $this->plates->render('error::error', ['error_code' => $error_code, 'error_message' => $error_message]);
    }
}
