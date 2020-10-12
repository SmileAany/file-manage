<?php

namespace Smbear\FileManage;

use Illuminate\Support\Facades\Route;
use Smbear\FileManage\Controllers\FileManageController;

//附件上传
Route::post('upload',[FileManageController::class,'upload']);