<?php

namespace Smile\FileManage\Exceptions;

use Exception;
use Throwable;

class FileManageException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}