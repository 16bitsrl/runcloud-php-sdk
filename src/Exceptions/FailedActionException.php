<?php
namespace App\RuncloudApi\Exceptions;

use Exception;

class FailedActionException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}