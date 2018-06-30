<?php

namespace App\Library\Application\Exception;

use Symfony\Component\HttpFoundation\Request;
use Throwable;

class CannotCreateBookBecauseOfMissingParametersException extends \Exception
{
    public function __construct(Request $request, int $code = 0, Throwable $previous = null)
    {
        $title = $request->get('title');
        $price = $request->get('price');
        $image = $request->get('image');
        $author = $request->get('author');

        $message = sprintf(
            'Needed parameters were not found in the given request [%s, %s, %s, %s]',
            $title, $price, $image, $author
        );

        parent::__construct($message, $code, $previous);
    }
}