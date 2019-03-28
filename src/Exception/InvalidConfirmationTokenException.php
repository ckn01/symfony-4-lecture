<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 28/03/19
 * Time: 19:36
 */

namespace App\Exception;

use Throwable;

class InvalidConfirmationTokenException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Confirmation token is invalid.', $code, $previous);
    }
}