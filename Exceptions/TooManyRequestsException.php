<?php
namespace App\RuncloudApi\Exceptions;

use Exception;

class TooManyRequestsException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('Too many requests. You have exceeded our rate limits.');
    }
}
