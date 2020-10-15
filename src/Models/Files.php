<?php

namespace Smbear\FileManage\Models;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;
use Smbear\FileManage\Filters\FilesFilter;

class Files extends Model
{
    use Filterable;

    protected $table = 'files';

    public $fillable = [
        'user_id','model_id','model_type','filename','original','ext','size','path','type'
    ];

    protected function modelFilter()
    {
        return $this->provideFilter(FilesFilter::class);
    }
}