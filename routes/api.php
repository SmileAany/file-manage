<?php

namespace Smile\FileManage;

use Illuminate\Support\Facades\Route;
use Smile\FileManage\Controllers\FileManageController;

//附件上传
Route::post('upload',[FileManageController::class,'upload']);