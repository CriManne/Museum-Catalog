<?php

    namespace Mupin\Exceptions;

    use Exception;

    class ServiceException extends Exception{

        public function __construct(string $msg)
        {
            $this->message = $msg;
        }

    }