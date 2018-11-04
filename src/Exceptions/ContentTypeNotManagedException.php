<?php
namespace App\RuncloudApi\Exceptions;

use Exception;

class ContentTypeNotManagedException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('Content type not managed.');
    }
}