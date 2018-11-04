<?php
namespace App\RuncloudApi\Exceptions;

use Exception;

class ForbiddenRequestException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('Forbiddden request.');
    }
}