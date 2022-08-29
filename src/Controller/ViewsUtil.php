<?php

declare(strict_types=1);

namespace App\Controller;

use League\Plates\Engine;

class ViewsUtil {

    protected Engine $plates;

    public function __construct(Engine $plates) {
        $this->plates = $plates;
    }

    public function displayError(int $error_code, string $error_message): string {
        return $this->plates->render('error::error', ['error_code' => $error_code, 'error_message' => $error_message]);
    }
}
