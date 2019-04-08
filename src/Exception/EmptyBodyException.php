<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 28/03/19
 * Time: 16:06
 */

namespace App\Exception;


use Throwable;

class EmptyBodyException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('The body of the POST/PUT method cannot be empty', $code, $previous);
    }
}