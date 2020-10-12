<?php

namespace Smbear\FileManage\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table = 'files';

    public $fillable = [
        'model_id','model_type','filename','original','ext','size','path','type'
    ];
}