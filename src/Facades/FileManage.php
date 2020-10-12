<?php

namespace Smbear\FileManage\Facades;

use Illuminate\Support\Facades\Facade;

class FileManage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'file.manage';
    }
}