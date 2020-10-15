<?php

namespace Smbear\FileManage\Filters;

use EloquentFilter\ModelFilter;

class FilesFilter extends ModelFilter
{
    protected $drop_id = false;

    protected $camel_cased_methods = false;

    //附件名称
    public function file_name($value)
    {
        return $this->where('filename','like','%'.$value.'%');
    }

    //用户姓名
    public function user_name($value)
    {
        return $this->whereHas('user',function ($query) use ($value){
            return $query->where('name','like','%'.$value.'%');
        });
    }

    //附件类型
    public function type($value)
    {
        return $this->where('ext',$value);
    }

    //附件上传时间
    public function date($value)
    {
        return $this->whereBetween('created_at',$value);
    }

    //附件作用区间
    public function scope($value)
    {
        return $this->where('model_type',$value);
    }
}