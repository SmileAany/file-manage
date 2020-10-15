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
use App\Models\Files;

class FileManageController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponse;

    /**
     * @describe
     * 附件上传
     * 将上传的的附件保存到
     * @param Request $request
     * @return mixed
     * @auth smile
     * @email ywjmylove@163.com
     * @date 2020-10-15 11:12
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file'  => 'required|file',
            'ext'   => 'filled|array',
            'limit' => 'filled|integer'
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

        $data = FileManage::upload($request);

        try {

            $data = array_merge([
                'user_id'=>auth()->user()->id
            ],$data);

            $id = Files::insertGetId($data);

            $data = [
                'id'       => $id,
                'filename' => $data['filename'],
                'url'      => Storage::url($data['path'])
            ];

            return $this->success(compact('data'));
        } catch (\Exception $exception) {
            throw new FileManageException($exception->getMessage());
        }
    }

    /**
     * @describe
     * 附件删除
     * @param Request $request
     * @return mixed
     * @auth smile
     * @email ywjmylove@163.com
     * @date 2020-10-15 11:50
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id'=>'required|integer',
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();

            foreach ($errors->all() as $message) {

                return $this->failed($message);
            }
        }

        return FileManage::destroy($request->input('id'));
    }

    /**
     * @describe
     * 附件重命名
     * @param Request $request
     * @return mixed
     * @auth smile
     * @email ywjmylove@163.com
     * @date 2020-10-15 11:50
     */
    public function rename(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id'=>'required|integer',
            'file_name'=>'required|string'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();

            foreach ($errors->all() as $message) {

                return $this->failed($message);
            }
        }

        return FileManage::rename($request->input('id'),$request->input('file_name'));
    }

    /**
     * @describe
     * 附件覆盖
     * @param Request $request
     * @return mixed
     * @auth smile
     * @email ywjmylove@163.com
     * @date 2020-10-15 11:51
     */
    public function cover(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file'=>'required|file',
            'ext' =>'filled|array',
            'limit'=>'filled|integer',
            'id'=>'required|integer',
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

        return FileManage::cover($request->input('id'),$request);
    }

    public function index(Request $request)
    {
        $res = Files::filter($request->all())->with('user')->paginate();

        return $this->success($res);
    }
}