<?php

namespace Smbear\FileManage\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Smbear\FileManage\Traits\ApiResponse;
use FileManage;

class FileManageController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponse;

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file'=>'required|file',
            'ext' =>'filled|array',
            'limit'=>'filled|integer'
        ],[
            'file.required' => '请上传附件',
            'file.file'     => '请上传附件',
            'ext.array'     => '附件类型必须为数组',
            'limit.integer' => '附件大小必须为整数'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();

            foreach ($errors->all() as $message) {

                return $this->failed($message);
            }
        }

        return FileManage::upload($request);
    }
}