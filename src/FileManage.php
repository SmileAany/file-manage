<?php

namespace Smile\FileManage;

use Smile\FileManage\Exceptions\FileManageException;
use Illuminate\Support\Facades\Storage;
use Smile\FileManage\Models\Files;
use Smile\FileManage\Traits\ApiResponse;

class FileManage
{
    use ApiResponse;

    public function upload(object $request)
    {
        if(!$request->file('file')->isValid()){
            throw new FileManageException('附件无效');
        }

        $ext = $request->file->getClientOriginalExtension();
        $exts = $request->input('ext',config('file.ext'));

        if(!in_array($ext,$exts)){
            throw new FileManageException('附件类型错误,只能上传 '.implode(",",$exts).' 格式附件');
        }

        $limit = $request->input('limit',config('file.limit'));
        $size = $request->file('file')->getSize();

        if($size > $limit*1024*1024){
            throw new FileManageException('附件超出最大限制'.custom_file_size_trans($limit*1024*1024));
        }

        if($size > $request->file('file')->getMaxFilesize()){
            throw new LimitException('附件超出最大限制'.custom_file_size_trans($request->file('file')->getMaxFilesize()));
        }

        if (!file_exists(storage_path('app\\public')) && !@mkdir(storage_path('app\\public'), 0777, true)) {
            throw new FileManageException('目录创建失败');
        } else if (!@is_writeable(storage_path('app\\public'))){
            throw new FileManageException('目录没有写权限');
        }

        $path     = Storage::disk('public')->putFile(date('Ym'),$request->file('file'));
        $filename = $request->file->getClientOriginalName();

        $params = [
            'filename'   => $filename,
            'original'   => substr($path, strpos($path, '/') + 1),
            'ext'        => $ext,
            'path'       => $path,
            'size'       => custom_file_size_trans($size),
            'type'       => $request->input('type',0),
            'created_at' => date('Y-m-d H:i:s')
        ];

        try{
            $id = Files::insertGetId($params);

            $data = [
                'id'      =>$id,
                'filename'=>$filename,
                'url'     =>Storage::url($path)
            ];

            return $this->success(compact('data'));
        }catch (\Exception $exception){
            throw new FileManageException($exception->getMessage());
        }
    }
}