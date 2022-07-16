<?php

    namespace Mupin\Exceptions;

    use Exception;

    class RepositoryException extends Exception{

        public function __construct(string $msg)
        {
            $this->message = $msg;
        }

    }