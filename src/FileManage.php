<?php

namespace Smbear\FileManage;

use Smbear\FileManage\Exceptions\FileManageException;
use Illuminate\Support\Facades\Storage;
use Smbear\FileManage\Models\Files;
use Smbear\FileManage\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class FileManage
{
    use ApiResponse;

    /**
     * @describe
     * 附件上传
     * @param object $request
     * @return mixed
     * @throws FileManageException
     * @auth smile
     * @email ywjmylove@163.com
     * @date 2020-10-14 17:35
     */
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

        $path     = Storage::disk(config('file.disk'))->putFile(date('Ym'),$request->file('file'));
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

        return $params;
    }

    /**
     * @describe
     * 附件删除
     * @param int $id
     * @return mixed
     * @throws FileManageException
     * @auth smile
     * @email ywjmylove@163.com
     * @date 2020-10-14 17:36
     */
    public function destroy(int $id)
    {
        try{
            $path = Files::where('id',$id)
                ->value('path');

            if($path){
                if(!Storage::disk(config('file.disk'))->exists($path)){
                    throw new FileManageException('资源不存在');
                }

                Files::destroy($id);
                Storage::disk(config('file.disk'))->delete($path);

                return $this->message('删除成功');
            }

            return $this->failed('附件id 不存在');
        }catch (\Exception $exception){
            throw new FileManageException('资源删除失败 '.$exception->getMessage());
        }
    }

    /**
     * @describe
     * 附件重命名
     * @param int $id
     * @param string $file_name
     * @return mixed
     * @throws FileManageException
     * @auth smile
     * @email ywjmylove@163.com
     * @date 2020-10-15 10:38
     */
    public function rename(int $id,string $file_name)
    {
        $file = Files::find($id);

        if($file){
            try{
                $file->filename = $file_name;
                $file->save();

                return $this->message('编辑成功');
            }catch (\Exception $exception){
                throw new FileManageException('资源编辑失败 '.$exception->getMessage());
            }
        }

        return $this->failed('附件id 不存在');
    }

    /**
     * @describe
     * 附件覆盖
     * @param int $id
     * @param object $request
     * @return mixed
     * @throws FileManageException
     * @auth smile
     * @email ywjmylove@163.com
     * @date 2020-10-15 10:48
     */
    public function cover(int $id,object $request)
    {
        $file = Files::find($id);

        if($file){

            try{
                Storage::disk(config('file.disk'))->delete($file->path);

                $data = $this->upload($request);

                $file->filename = $data['filename'];
                $file->original = $data['original'];
                $file->ext      = $data['ext'];
                $file->path     = $data['path'];
                $file->size     = $data['size'];

                $file->save();

                return $this->message('覆盖成功');
            }catch (\Exception $exception){
                throw new FileManageException('资源编辑失败 '.$exception->getMessage());
            }
        }

        return $this->failed('附件id 不存在');
    }

    /**
     * @describe
     * 附件列表
     * @param object $request
     * @return mixed
     * @auth smile
     * @email ywjmylove@163.com
     * @date 2020-10-16 15:26
     */
    public function index(object $request)
    {
        return Files::filter($request->all())->with('user')->paginate();
    }

}