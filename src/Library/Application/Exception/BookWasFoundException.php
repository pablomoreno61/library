<?php

namespace App\Library\Application\Exception;

use Throwable;

class BookWasFoundException extends \Exception
{
    public function __construct(string $uuid = "", int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Book was not found for the given uuid [%s]', $uuid);

        parent::__construct($message, $code, $previous);
    }
}