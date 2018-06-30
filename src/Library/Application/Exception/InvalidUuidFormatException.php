<?php

namespace App\Library\Application\Exception;

use Throwable;

class InvalidUuidFormatException extends \Exception
{
    public function __construct(string $uuid = "", int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Invalid uuid [%s]', $uuid);

        parent::__construct($message, $code, $previous);
    }
}