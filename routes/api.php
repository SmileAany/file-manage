<?php

namespace Smbear\FileManage;

use Illuminate\Support\Facades\Route;
use Smbear\FileManage\Controllers\FileManageController;

//附件上传
Route::post('upload',[FileManageController::class,'upload']);

//附件删除
Route::delete('destroy',[FileManageController::class,'destroy']);

//附件重命名
Route::put('rename',[FileManageController::class,'rename']);

//附件覆盖
Route::put('cover',[FileManageController::class,'cover']);

//附件列表
Route::get('list',[FileManageController::class,'index']);
